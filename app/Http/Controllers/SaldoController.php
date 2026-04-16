<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Summary;
use Carbon\Carbon;

class SaldoController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // ── Per-account current balance — single GROUP BY query ───────────────
        $balancesByAccount = Summary::where("status", "PAID")
            ->where("date", "<=", $today)
            ->selectRaw(
                "account_id, SUM(CASE WHEN type='add' THEN amount ELSE -amount END) as net",
            )
            ->groupBy("account_id")
            ->pluck("net", "account_id");

        $accounts = Account::all()->each(function (Account $account) use (
            $balancesByAccount,
        ) {
            $account->setAttribute(
                "total",
                (float) ($balancesByAccount[$account->id] ?? 0),
            );
        });

        $totalfinal = $accounts->sum("total");

        // ── Future movements (date > today, PAID) — 2 aggregate queries ───────
        $futureBase = Summary::where("status", "PAID")->where(
            "date",
            ">",
            $today,
        );
        $futureAdd = (clone $futureBase)->where("type", "add")->sum("amount");
        $futureOut = (clone $futureBase)->where("type", "out")->sum("amount");
        $futureNet = $futureAdd - $futureOut;

        // ── Liquidity projections — 1 query per horizon ───────────────────────
        $liquidityAt = fn(int $days): float => (float) Summary::where(
            "status",
            "PAID",
        )
            ->where(
                "date",
                "<=",
                Carbon::today()->addDays($days)->toDateString(),
            )
            ->selectRaw(
                "SUM(CASE WHEN type='add' THEN amount ELSE -amount END) as net",
            )
            ->value("net");

        return view("admin.saldos.totales", [
            "accounts" => $accounts,
            "totalfinal" => $totalfinal,
            "futureAdd" => $futureAdd,
            "futureOut" => $futureOut,
            "futureNet" => $futureNet,
            "totalm1" => $liquidityAt(30),
            "totalm3" => $liquidityAt(90),
            "totalm6" => $liquidityAt(180),
            "today" => $today,
        ]);
    }
}

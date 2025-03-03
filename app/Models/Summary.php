<?php

namespace App\Models;

use App\Events\SummaryCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'concept',
        'type',
        'status',
        'nulled_motive',
        'amount',
        'tipo_cambio',
        'tax',
        'recipt_series',
        'recipt_number',
        'future',
        'operation_number',
        'observation',
        'paid_by',
        'account_id',
        'id_transfer',
        'user_id',
        'customer_id',
        'student_id',
        'student_tutor_id',
        'payment_method_id',

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'date' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $serie_ingreso = $model->account->add_serie;// Si el tipo de movimiento es 'add', asignar la serie 'RI01' y el siguiente número correlativo
            $serie_gasto = $model->account->out_serie;// Si el tipo de movimiento es 'out', asignar la serie 'RG01' y el siguiente número correlativo
            if($model->status == 'PAID'){

                if ($model->type === 'add') {

                    $lastIncomeSummary = Summary::where('type', '=', 'add')->where('recipt_series', $serie_ingreso)->max('recipt_number');
                    // dd($lastIncomeSummary);
                    $nextNumber = $lastIncomeSummary ? $lastIncomeSummary + 1 : 1;
                    $model->recipt_series = $serie_ingreso;
                    $model->recipt_number = $nextNumber;

                } elseif ($model->type === 'out') {

                    $lastExpenseSummary = Summary::where('type', '=', 'out')->where('recipt_series', $serie_gasto)->max('recipt_number');
                    $nextNumber = $lastExpenseSummary ? $lastExpenseSummary + 1 : 1;
                    $model->recipt_series = $serie_gasto;
                    $model->recipt_number = $nextNumber;

                }
            }

        });

        self::updating(function ($model) {
            $serie_ingreso = $model->account->add_serie;// Si el tipo de movimiento es 'add', asignar la serie 'RI01' y el siguiente número correlativo
            $serie_gasto = $model->account->out_serie;// Si el tipo de movimiento es 'out', asignar la serie 'RG01' y el siguiente número correlativo
            if($model->status == 'PAID' and $model->recipt_series == null and $model->recipt_num_series == null ){

                if ($model->type === 'add') {
                    $lastIncomeSummary = Summary::where('type', '=', 'add')->where('recipt_series', $serie_ingreso)->max('recipt_number');
                    // dd($lastIncomeSummary);
                    $nextNumber = $lastIncomeSummary ? $lastIncomeSummary + 1 : 1;
                    $model->recipt_series = $serie_ingreso;
                    $model->recipt_number = $nextNumber;
                    // dd($nextNumber);

                } elseif ($model->type === 'out') {
                    $lastExpenseSummary = Summary::where('type', '=', 'out')->where('recipt_series', $serie_gasto)->max('recipt_number');
                    $nextNumber = $lastExpenseSummary ? $lastExpenseSummary + 1 : 1;
                    $model->recipt_series = $serie_gasto;
                    $model->recipt_number = $nextNumber;
                }
            }

        });
    }

    protected static function booted()
    {
        static::created(function ($model) {

            $serie_ingreso = $model->account->add_serie;
            $serie_gasto = $model->account->out_serie;

            if ($model->type === 'add') {
                $serie = $serie_ingreso;
            } else {
                $serie = $serie_gasto;
            }

            $receipt = $serie . str_pad($model->receipt_number, 8, '0', STR_PAD_LEFT);

            // Mostrar la modal
            event(new SummaryCreated($model, $receipt));
        });
    }



    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function tutor(){
        return $this->belongsTo(StudentTutor::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
    public function details(){
        return $this->hasMany(Detail::class);
    }

    public function nulledDetails(){
        return $this->hasMany(NulledDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}

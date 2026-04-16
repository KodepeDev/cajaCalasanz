<?php

namespace App\Http\Controllers;

use App\Models\AttrValue;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('id', '!=', 1)
            ->withCount('details')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('admin.categorias.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'type'        => 'required|in:add,out',
            'description' => 'nullable|string|max:200',
        ]);

        $category = Category::create($data);

        return redirect("/admin/categories/edit/{$category->id}")
            ->with('success', 'Categoría creada. Puede agregar subcategorías a continuación.');
    }

    public function edit(int $id)
    {
        $category   = Category::findOrFail($id);
        $attributes = AttrValue::where('category_id', $id)->get();

        return view('admin.categorias.edit', compact('category', 'attributes'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'type'        => 'required|in:add,out',
            'description' => 'nullable|string|max:200',
            'name_.*'     => 'nullable|string|max:200',
            'value_.*'    => 'nullable|string|max:200',
        ]);

        $category->update([
            'name'        => $data['name'],
            'type'        => $data['type'],
            'description' => $data['description'] ?? null,
        ]);

        $names  = $request->input('name_', []);
        $values = $request->input('value_', []);
        $ids    = $request->input('id', []);

        foreach ($names as $n => $name) {
            if (blank($name)) {
                continue;
            }

            $attrId = (int) ($ids[$n] ?? 0);

            if ($attrId > 0) {
                AttrValue::where('id', $attrId)->update([
                    'name'        => $name,
                    'value'       => $values[$n] ?? '',
                    'category_id' => $id,
                ]);
            } else {
                AttrValue::create([
                    'name'        => $name,
                    'value'       => $values[$n] ?? '',
                    'category_id' => $id,
                ]);
            }
        }

        return redirect(route('categorias'))
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        $category = Category::withCount('details')->findOrFail($id);

        if ($category->details_count > 0) {
            return redirect(route('categorias'))
                ->with('error', 'No se puede eliminar: la categoría tiene detalles asociados.');
        }

        AttrValue::where('category_id', $id)->delete();
        $category->delete();

        return redirect(route('categorias'))
            ->with('success', 'Categoría eliminada correctamente.');
    }

    // ── Subcategory attr routes ───────────────────────────────────────────────

    public function view_attr(int $id)
    {
        $categorie = Category::findOrFail($id);

        return view('admin.categorias.attr', compact('categorie'));
    }

    public function save_attr(Request $request, int $id)
    {
        $request->validate([
            'name_.*'  => 'required|string|max:200',
            'value_.*' => 'nullable|string|max:200',
        ]);

        $names  = $request->input('name_', []);
        $values = $request->input('value_', []);

        foreach ($names as $n => $name) {
            if (blank($name)) {
                continue;
            }

            AttrValue::create([
                'name'        => $name,
                'value'       => $values[$n] ?? '',
                'category_id' => $id,
            ]);
        }

        return redirect(route('categorias'))
            ->with('success', 'Subcategorías guardadas correctamente.');
    }

    public function destroyattr(int $id)
    {
        AttrValue::findOrFail($id)->delete();

        return back()->with('success', 'Subcategoría eliminada.');
    }

    public function get_all(int $id)
    {
        return response()->json(
            AttrValue::where('category_id', $id)->get()
        );
    }
}

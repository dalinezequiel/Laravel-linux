<?php

namespace App\Http\Controllers\Site\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $success = session('success');
        $customers = Customer::paginate(4);
        return Inertia::render('Site/Customer/Index', compact('success', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Site/Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        \Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'email' => ['required', 'string'],
            'birthday' => ['required', 'string'],
            'status' => ['required', 'string'],
        ])->validate();
        // dd($request->all());

        try {
            DB::beginTransaction();
            Customer::create([
                'firstname' => $request->firstname,
                'surname' => $request->surname,
                'email' => $request->email,
                'birthday' => $request->birthday,
                'status' => $request->status,
            ]);

            DB::commit();
            session()->flush('success', 'Adicionado cm sddd');
            return redirect()
                ->route('customers.index')
                ->with('success', 'Adicionado cm sddd');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *@param int $id;
    */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return Inertia::render('Site/Customer/Edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        \Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'email' => ['required', 'string'],
            'birthday' => ['required', 'string'],
            'status' => ['required', 'string'],
        ])->validate();

        try {
            //code...
            DB::beginTransaction();
            Customer::updateOrCreate(['id' => $id], [
                'firstname' => $request->firstname,
                'surname' => $request->surname,
                'email' => $request->email,
                'birthday' => $request->birthday,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
            ->route('customers.index')
            ->with('Sucess', 'Cliente Actualizado com Sucesso');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors([ 'error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * *@param int $id;
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Customer::find($id)->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => '',
                'message' => 'Cliente removido com sucesso!',
            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Impossivel remover o cliente!',
                    'message' => 'Impossivel remover o cliente!',
                ],
                501
            );
        }
    }
}

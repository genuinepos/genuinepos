<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Contacts\ContactStoreRequest;
use App\Http\Requests\Contacts\ContactUpdateRequest;
use App\Interfaces\Contacts\ContactControllerMethodContainersInterface;

class ContactController extends Controller
{
    public function create($type, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        if ($type == ContactType::Customer->value) {

            abort_if(!auth()->user()->can('customer_add') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        } elseif ($type == ContactType::Supplier->value) {

            abort_if(!auth()->user()->can('supplier_add') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        }

        $createMethodContainer = $contactControllerMethodContainersInterface->createMethodContainer(type: $type);

        extract($createMethodContainer);

        return view('contacts.ajax_view.create', compact('type', 'customerGroups'));
    }

    public function store($type, ContactStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $contactControllerMethodContainersInterface->storeMethodContainer(type: $type, request: $request, codeGenerator: $codeGenerator);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $storeMethodContainer;
    }

    public function edit($id, $type, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        if ($type == ContactType::Customer->value) {

            abort_if(!auth()->user()->can('customer_edit') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        } elseif ($type == ContactType::Supplier->value) {

            abort_if(!auth()->user()->can('supplier_edit') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        }

        $editMethodContainer = $contactControllerMethodContainersInterface->editMethodContainer(id: $id, type: $type);

        extract($editMethodContainer);

        return view('contacts.ajax_view.edit', compact('type', 'contact', 'customerGroups'));
    }

    public function update($id, $type, ContactUpdateRequest $request, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $contactControllerMethodContainersInterface->updateMethodContainer(id: $id, type: $type, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contact is updated successfully.'));
    }

    public function changeStatus($id, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        return response()->json($contactControllerMethodContainersInterface->changeStatusMethodContainer(id: $id));
    }

    public function delete($id, $type, Request $request, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
        if ($type == ContactType::Customer->value) {

            abort_if(!auth()->user()->can('customer_delete') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        } elseif ($type == ContactType::Supplier->value) {

            abort_if(!auth()->user()->can('supplier_delete') || config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value, 403);
        }

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $contactControllerMethodContainersInterface->deleteMethodContainer(id: $id, type: $type);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contact deleted successfully'));
    }
}

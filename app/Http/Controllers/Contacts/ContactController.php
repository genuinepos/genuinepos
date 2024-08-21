<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\ContactEditRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Contacts\ContactStoreRequest;
use App\Http\Requests\Contacts\ContactCreateRequest;
use App\Http\Requests\Contacts\ContactDeleteRequest;
use App\Http\Requests\Contacts\ContactUpdateRequest;
use App\Interfaces\Contacts\ContactControllerMethodContainersInterface;

class ContactController extends Controller
{
    public function create($type, ContactCreateRequest $request, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
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

    public function edit($id, $type, ContactEditRequest $request, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
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

    public function delete($id, $type, ContactDeleteRequest $request, ContactControllerMethodContainersInterface $contactControllerMethodContainersInterface)
    {
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

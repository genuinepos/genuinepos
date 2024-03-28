<?php

namespace App\Http\Traits\File;


trait FileUploadTrait
{

    public function single($request, $path, $inputName)
    {
        $image = $request->file($inputName);
        if ($image) {
            $imageName = time() . '.' . $image->getClientOriginalName();
            $image->move(public_path($path), $imageName);
            return $imageName;
        }
        return null;
    }

    public function multiple($data, $path)
    {
        if ($data && is_array($data)) {
            $imageNames = [];
            foreach ($data as $key => $image) {
                if ($image) {
                    $imageName = time() . '_' . $key . '.' . $image->getClientOriginalName();
                    $image->move(public_path($path), $imageName);
                    $imageNames[] = $imageName;
                }
            }
            return $imageNames;
        }
        return null;
    }
}

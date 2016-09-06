<?php

class UploadController extends BaseController
{

    public function postGeneralClaimsEntry($mask)
    {
        $data = Input::all();
        $file = Input::file('file');
        $file_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, ['png', 'jpg'])) {
            $thumb_filename = 'thumb__' . $file_name . '.png';
        } else {
            $thumb_filename = 'thumb__' . $file_name;
        }

        $entriesFolder = public_path() . '/uploads/general-claims/' . $mask . '/';
        if (!is_dir($entriesFolder)) {
            mkdir($entriesFolder, 0777, true);
        }

        $data = [
            'mask' => $mask,
            'file_name' => $file_name,
            'size' => $file->getSize(),
            'imageable_type' => 'GeneralClaim__Entry',
            'file_url' => '/uploads/general-claims/' . $mask . '/' . $file_name,
            'file_path' => $entriesFolder . $file_name,
            'thumb_url' => '/uploads/general-claims/' . $mask . '/' . $thumb_filename,
            'thumb_path' => $entriesFolder . $thumb_filename,
        ];

        $upload = Upload::create($data);

        $file->move($entriesFolder, $file_name);

        Helper::thumb($upload);

        return $upload;
    }

    public function postDo($model, $path, $itemId)
    {
        $data = Input::all();
        $user = Auth::user();
        $file = Input::file('file');
        $file_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mask = md5(microtime());
        $new_filename = $mask . '.' . $extension;
        $thumb_filename = $mask . '__thumb.' . $extension;

        /* create upload directory */

        $entriesFolder = public_path() . '/uploads/' . $path . '/';

        if (!is_dir($entriesFolder)) {
            mkdir($entriesFolder, 0777, true);
        }

        /* insert into database */

        $data = [
            'mask' => $mask,
            'file_name' => $file_name,
            'size' => $file->getSize(),
            'file_url' => '/uploads/' . $path . '/' . $new_filename,
            'file_path' => $entriesFolder . $new_filename,
            'thumb_url' => '/uploads/' . $path . '/' . $thumb_filename,
            'thumb_path' => $entriesFolder . $thumb_filename,
        ];

        if (is_numeric($itemId)) {
            switch ($model) {
                case 'users':
                    $item = User::find($itemId);
                    break;
                case 'leave':
                    $item = Leave__Main::find($itemId);
                    break;
                case 'medicalclaim':
                    $item = MedicalClaim__Main::find($itemId);
                    break;
                case 'generalclaim':
                    $item = GeneralClaim__Main::find($itemId);
                    break;
            }
            $entryFile = $item->uploads()->create($data);
        } else {
            $data['imageable_type'] = $itemId;
            $entryFile = Upload::create($data);
        }

        /* move uploaded file */

        $file->move($entriesFolder, $new_filename);

        Helper::resizeImage2($entryFile->file_path, $entryFile->thumb_path, 200);

        return Response::json($entryFile);
    }

    public function getRemove($id)
    {
        $entryFile = Upload::find($id);
        if (!$entryFile && !in_array($id, ['Leave__Main', 'MedicalClaim__Main', 'GeneralClaim__Main'])) {
            $entryFile = Upload::where('mask', $id)->first();
        }

        if (!$entryFile) {
            return App::abort(404);
        }
        $entryFile->delete();
    }
}

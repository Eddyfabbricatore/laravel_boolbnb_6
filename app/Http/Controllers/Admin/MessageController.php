<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewContact;
use App\Models\Apartment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function messagesForApartment($slug){

        $apartment = Apartment::where('slug', $slug)->with('messages')->firstOrFail();

        $id = $apartment->id;

        // dd($apartment);
        $messages = Message::with('apartment')->where('apartment_id' , $id)->orderByDesc('date')->paginate(10);
        return view('admin.apartments.messages', compact('messages'));
    }



    public function store(Request $request){
        $data_message_user = $request->all();

        $apt_id = $data_message_user['apartment_id'];


        $validator = Validator::make($data_message_user,[
            'email'=> 'required|min:2|max:255',
            'message'=> 'required|min:2',
        ],
        [
            'email.min' => 'l\'email deve avere :min caratteri',
            'email.max' => 'l\' email deve avere :max caratteri',
            'message.required' => 'il message è un campo obbligatorio',
            'message.min' => 'il message deve avere :min caratteri',
            'status.type' => 'ciao'
        ]
    );

    if($validator->fails()){
        $success = false;
        $errors = $validator->errors();
        return response()->json(compact('success','errors'));
    }
    $new_message = new Message();

    $new_message->apartment_id = $apt_id;

    $new_message->fill($data_message_user)->save();
    // $new_message->save();

    // Mail::to('provamessaggio@example.com')->send(new NewContact($new_message));

    $success = true;

    return response()->json(compact('success'));
    }
}

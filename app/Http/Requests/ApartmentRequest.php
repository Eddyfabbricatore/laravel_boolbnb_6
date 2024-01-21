<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // "user_id" => "required | numeric",
            "title" => "required | string | min:3 | max:255",
            // "image" => "string | min:3 | max:500",
            "rooms" => "required | numeric | min:1 | max:255",
            "bathrooms" => "required | numeric | min:1 | max:255",
            "beds" => "required | numeric | min:1 | max:255",
            "square_meters" => "required | numeric | min:1 | max:65535",
            "address" => "required | string | min:5 | max:255",
/*             "lat" => "required | numeric",
            "lng" => "required | numeric", */
            "visible" => "required | boolean",
        ];
    }

    public function messages() {
        return [
            //TODO: gestire errore lato server reindirizzando a una pagina d'errore riguardo user_id
            // 'user_id.required' => 'Il campo user_id è obbligatorio.',
            // 'user_id.numeric' => 'Il campo user_id deve essere un valore numerico.',

            'title.required' => 'Il titolo è obbligatorio.',
            'title.string' => 'Il titolo deve essere una stringa.',
            'title.min' => 'Il titolo deve avere almeno :min caratteri.',
            'title.max' => 'Il titolo non può superare i :max caratteri.',

            'image.required' => 'L\'immagine è obbligatoria.',
            'image.string' => 'L\'immagine deve essere una stringa.',
            'image.min' => 'L\'indirizzo dell\'immagine deve avere almeno :min caratteri.',
            'image.max' => 'L\'indirizzo dell\'immagine non può superare i :max caratteri.',

            'rooms.required' => 'Il numero di stanze è obbligatorio.',
            'rooms.numeric' => 'Il numero di stanze deve essere un valore numerico.',
            'rooms.min' => 'Il numero di stanze deve essere almeno :min.',
            'rooms.max' => 'Il numero di stanze non può superare :max.',

            'bathrooms.required' => 'Il numero di bagni è obbligatorio.',
            'bathrooms.numeric' => 'Il numero di bagni deve essere un valore numerico.',
            'bathrooms.min' => 'Il numero di bagni deve essere almeno :min.',
            'bathrooms.max' => 'Il numero di bagni non può superare :max.',

            'beds.required' => 'Il numero di letti è obbligatorio.',
            'beds.numeric' => 'Il numero di letti deve essere un valore numerico.',
            'beds.min' => 'Il numero di letti deve essere almeno :min.',
            'beds.max' => 'Il numero di letti non può superare :max.',

            'square_meters.required' => 'Il numero di metri quadrati è obbligatorio.',
            'square_meters.numeric' => 'Il numero di metri quadrati deve essere un valore numerico.',
            'square_meters.min' => 'Il numero di metri quadrati deve essere almeno :min.',
            'square_meters.max' => 'Il numero di metri quadrati non può superare :max.',

            'address.required' => 'L\'indirizzo è obbligatorio.',
            'address.string' => 'L\'indirizzo deve essere una stringa.',
            'address.min' => 'L\'indirizzo deve essere almeno :min caratteri.',
            'address.max' => 'L\'indirizzo non può superare i :max caratteri.',

            'lat.required' => 'Il campo lat è obbligatorio.',
            'lat.numeric' => 'Il campo lat deve essere un valore numerico.',

            'lng.required' => 'Il campo lng è obbligatorio.',
            'lng.numeric' => 'Il campo lng deve essere un valore numerico.',

            'visible.required' => 'Il campo visible è obbligatorio.',
            'visible.boolean' => 'Il campo visible deve essere 0 o 1.',
        ];
    }
}

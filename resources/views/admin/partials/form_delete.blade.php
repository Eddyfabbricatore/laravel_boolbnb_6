<form
    action="{{ $route }}"
    method="POST"
    onsubmit="return confirm ('{{$message}}')"
    class="d-inline-block">
    @csrf
    @method("DELETE")
    <button
            type="submit"
            class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></button>
</form>

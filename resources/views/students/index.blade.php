@extends('layouts.plantillaMain')

@section('title', 'Student')

@section('script')
    <script>
function friendshipClick() {
    window.location.href = "{{ route('students.friendship') }}";
}
function chtClick(){

}

function study_roomClick(){

}

function tutorialsClick(){

}
/*
function redirection(index) {
        switch(index){
            case 1:
                window.location.href = "#";
                break;
            case 2:
                window.location.href = "#";
                break;
            case 3:
                window.location.href = "#";
                break;
            case 4:
                window.location.href = "#";
                break;
            case 5:
                window.location.href = "#";
                break;
            case 6:
                window.location.href = "#";
                break;
            case 7:
                window.location.href = "#";
                break;
            case 8:
                window.location.href = "{{route('students.actual_fruends')}}";
                break;
            case 9:
                window.location.href = "{{route('students.friendship')}}";
                break;
            case 10:
                window.location.href = "{{route('users.modify')}}";
                break;
            case 11:
                window.location.href = "{{route('users.delete')}}";
                break;
            case 12:
                window.location.href = "{{route('users.close')}}";
                break;
        }
    }*/
    </script>
@endsection



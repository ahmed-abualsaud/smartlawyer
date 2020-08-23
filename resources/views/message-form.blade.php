<script>

    function sendMessage(id){
        Swal.fire({
            title: "{{__('dashboard.write_message')}}",
            input: 'textarea',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: false,
            confirmButtonText: "{{__('dashboard.send')}}",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    'url':'{{route('messages.sendMessage')}}',
                    'type':'POST',
                    'data':{
                        'id' : id,
                        'message' : result.value,
                        'type' : "{{$type}}",
                        "_token": "{{ csrf_token() }}",
                    },
                    success:function(response){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: "{{__('dashboard.send_successfully')}}",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    },
                    error : function(response){
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: "{{__('dashboard.failed_request')}}",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        })
    }
</script>

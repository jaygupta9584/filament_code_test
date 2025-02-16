<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('submit-form') }}" method="POST">
        @csrf
        <input type="text" name="name" required>
        <input type="email" name="email" required>
        <button type="submit">Submit</button>
    </form>

    <script>
        $(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('submit-form') }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        alert(response.message);
                    }
                });
            });
        }
           

        });
    </script>
</body>

</html>
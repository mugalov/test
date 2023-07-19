console.log('asdf')

$(document).on('change', '#file-file', function (event) {
    console.log('asdf')
    event.preventDefault();

    var form = $('#w0');
    console.log(form.attr('method'))
    var formData = new FormData(form[0]);
    
    $.ajax({
        url: '/site/upload',
        type: form.attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            if (response.success == true) {
                document.querySelector('.error-message').innerHTML = 'Файл успешно загружен'
                document.querySelector('#file-id').value = response.data.id;

            } else if(response.success == false) {
                document.querySelector('#file-id').value = response.data.id;
                document.querySelector('#file-description').value = response.data.description;

                document.querySelector('.error-message').innerHTML = '<b>Такой файл есть в базе данных</b><br>'+response.data.name + ' <br> ' + response.data.size
            }
        },
        error: function () {
            document.querySelector('.error-message').innerHTML = 'Не удалось отправить данные'
        }
    });

    return false;
});


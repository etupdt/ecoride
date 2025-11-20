
const photo = document.querySelector('#compte_form_photo')

if (photo !== null) {

    photo.addEventListener('change', function(e) {
    
        document.querySelector('#compte_image').setAttribute('src', URL.createObjectURL(e.target.files[0]));

        return false;
    
    })

}


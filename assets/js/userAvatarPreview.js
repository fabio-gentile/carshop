const imageInput = document.querySelector('[data-avatar = "preview"]');
const previewImage = document.querySelector('#previewImage');
function previewSelectedImage() {
    const file = imageInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block'
        }
    }
}
imageInput.addEventListener('change', previewSelectedImage);
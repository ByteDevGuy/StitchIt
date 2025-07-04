function toggleCustomNameField() {
    const select = document.getElementById('product');
    const customDiv = document.getElementById('customNameDiv');
    const customInput = document.getElementById('customProduct');

    if (select.value === 'custom') {
        customDiv.style.display = 'block';
        customInput.required = true;
    } else {
        customDiv.style.display = 'none';
        customInput.required = false;
    }
}

document.getElementById('reference_image').addEventListener('change', function() {
    const [file] = this.files;
    const preview = document.getElementById('preview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
});

document.getElementById("reference_image").addEventListener("change", function (e) {
    const preview = document.getElementById("preview");
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        preview.style.maxWidth = '200px';
        preview.style.marginTop = '10px';
    }
});

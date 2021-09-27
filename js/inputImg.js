function updateImageDisplay() {
    while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }
    var curFiles = input.files;
    var para = document.createElement('p');
    if (curFiles.length === 0) {
        para.textContent = 'Pas de fichier corretement selectionné';
        preview.appendChild(para);
    } else {
        if (curFiles.length === 1) {
            var theFile = curFiles[0];
            var listItem = document.createElement('div');
            if (validFileType(theFile)) {
                var image = document.createElement('img');
                image.src = window.URL.createObjectURL(theFile);
                para.textContent = 'Votre avatar';
                
                preview.appendChild(para);
                preview.appendChild(image);
            } else {
                para.textContent = 'Nom du fichier : ' + theFile.name + ' : Type de fichier non valide';
                preview.appendChild(para);
            }
        }
    }
}
function validFileType(file) {
    var fileTypes = [
        'image/jpeg',
        'image/png',
        'image/jpg'
    ];
    for (var i = 0; i < fileTypes.length; i++) {
        if (file.type === fileTypes[i]) {
            return true;
        }
    }
    return false;
}
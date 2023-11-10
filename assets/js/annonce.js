const addImage = document.querySelector('#add-image')
addImage.addEventListener('click', () => {
    // compter nb form-group pour les indices : ex : annonce_image_0_url
    const widgetCounter = document.querySelector('#widgets-counter')
    const index = +widgetCounter.value // + : String => numerique
    const annonceImages = document.querySelector('#advert_advertImages')

    // recup le prototype dans la div
    const prototype = annonceImages.dataset.prototype.replace(/__name__/g, index)
    // g pour indiquer que l'on va le faire plusieurs fois
    annonceImages.insertAdjacentHTML('beforeend', prototype)
    widgetCounter.value = index + 1

    // mettre a jour la table deletes
    handleDeleteButton()
})

const updateCounter = () => {
    const count = document.querySelectorAll('#advert_advertImages div.form-group').length
    document.querySelector('#widgets-counter').value = count
}

const handleDeleteButton = () => {
    const deletes = document.querySelectorAll('button[data-action="delete"]')

    deletes.forEach(e => {
        e.addEventListener('click', () => {
            const target = e.dataset.target
            const elementTarget = document.querySelector(target)
            if (elementTarget) {
                elementTarget.remove()
            }
        })
    })
}
updateCounter()
handleDeleteButton()
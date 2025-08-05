import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        addLabel: String,
        deleteLabel: String
    }

    static targets = ["collectionContainer"]

    connect() {
        this.index = this.collectionContainerTarget.children.length

        // Add the "add a ..." link
        this.addFormDeleteLink()
    }

    addCollectionElement(event) {
        event.preventDefault()

        // Get the data-prototype explained earlier
        const collectionHolder = this.collectionContainerTarget
        const prototype = collectionHolder.dataset.prototype

        // get the new index
        const index = this.index

        let newForm = prototype
        // You need a unique index for every new element
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index)

        // increase the index with one for the next item
        this.index++

        // Display the form in the page in an li, before the "Add a ..." link li
        const newFormLi = document.createElement('div')
        newFormLi.innerHTML = newForm
        collectionHolder.appendChild(newFormLi)

        // add a delete link to the new form
        this.addFormDeleteLink(newFormLi)
    }

    addFormDeleteLink(item) {
        if (!item) {
            // Add delete links to existing items
            const items = this.collectionContainerTarget.children
            for (let i = 0; i < items.length; i++) {
                this.addFormDeleteLink(items[i])
            }
            return
        }

        // Check if delete link already exists
        if (item.querySelector('.delete-item')) {
            return
        }

        const removeFormButton = document.createElement('button')
        removeFormButton.type = 'button'
        removeFormButton.className = 'btn btn-danger btn-sm delete-item'
        removeFormButton.innerText = this.deleteLabelValue || 'Remove'

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault()
            // remove the li for the tag form
            item.remove()
        })

        item.appendChild(removeFormButton)
    }
}

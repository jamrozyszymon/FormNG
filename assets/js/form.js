var $addFormToCollection = $('<button class="add_new">Dodaj Nowe Pole</button>');
var $collectionHolder;

$(function() {

    $collectionHolder = $('#tasks');
    $collectionHolder.append($addFormToCollection);

    $collectionHolder.data('i', $collectionHolder.find('.card').lenght);

    $addFormToCollection.on("click", function() {
        addFormToCollection();
    })
});

function addFormToCollection() {

    var prototype = $collectionHolder.data('prototype');
    var i = $collectionHolder.data('i');
    var newForm=prototype;

    newForm = newForm.replace(/__name__/g, i);
    $collectionHolder.data('i', i++);

    var $card = $('<div class="card-task"></div>').append(newForm);
    $addFormToCollection.before($card);
}

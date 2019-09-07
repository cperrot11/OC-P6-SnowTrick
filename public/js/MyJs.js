var $collectionHolder;

// setup an "add a tag" link
var $addPictButton = $('<button type="button" class="action mt-2">Nouvelle image</button>');
var $addVideoButton = $('<button type="button" class="action mt-2">Nouvelle vidéo</button>');
var $newLinkLi = $('<span></span>').append($addPictButton);
var $newLinkLi2 = $('<span></span>').append($addVideoButton);
var demoTimeout;

jQuery(document).ready(function() {

    // Get the ul that holds the collection of picture/video
    $collectionHolder1 = $('ul.medias_pictures');


    // add a delete link to all of the existing tag form li elements
    $collectionHolder1.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder1.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder1.data('index', $collectionHolder1.find(':input').length);

    $addPictButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addArticleForm($collectionHolder1, $newLinkLi);
        $('#article_media___name___rec').on('click',function(a) {
            a.preventDefault();
            var adresse = "{{ path('blog') }}";
            alert(adresse);
            $.ajax({
                url: "{{ path('trick_new') }}",
                method: "POST",
                data: {
                    "media_name": 'tot'
                }
            });
        });
    });
    //Same for video
    $collectionHolder2 = $('ul.medias_videos');
    $collectionHolder2.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $collectionHolder2.append($newLinkLi2);
    $collectionHolder2.data('index', $collectionHolder2.find(':input').length);

    $addVideoButton.on('click', function(e) {
        addArticleForm($collectionHolder2, $newLinkLi2);
        $('#article_media___name___rec').on('click',function(a) {
            a.preventDefault();
            var adresse = "{{ path('blog') }}";
            alert(adresse);
            $.ajax({
                url: "{{ path('trick_new') }}",
                method: "POST",
                data: {
                    "media_name": 'tot'
                }
            });
        });
    });
    cp_tremble();

});

function addArticleForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}
function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="action fas fa-trash-alt text-right" title="Effacer"></button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
};
function cp_tremble() {
    $(".cpTremble").jrumble({
        x: 2,
        y: 2,
        rotation: 1,
        speed: 50
    });
    $(".cpTremble").hover(function(){
        $this = $(this);
        clearTimeout(demoTimeout);
        $(this).trigger("startRumble");
        demoTimeout = setTimeout(function(){$this.trigger("stopRumble");}, 600);
    }, function(){
        $(this).trigger("stopRumble");
    });
}


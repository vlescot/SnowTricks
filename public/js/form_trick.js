/**
 * Help functions to get the picture and video containers
 */
function getContainers(clickableElement) {

    let parentClass = $(clickableElement).parent().attr("class");

    if(parentClass.indexOf("pictures-control") !== -1){
        return container = $(clickableElement).closest($(".picture-container"));

    } else if (parentClass.indexOf("videos-control") !== -1) {
        return container = $(clickableElement).closest($(".video-container"));
    }
}

/**
 * Adjusting Height's .picture-container
 */
function pictureContainerHeight() {
    imgHeight = [];
    addPicture = $(".picture-container");

    addPicture.each(function () {
        imgHeight.push($(this).height());
    });

    maxHeight = Math.max.apply(Math, imgHeight);

    addPicture.each(function () {
        $(this).height(maxHeight);
    });
}

/**
 * Add a picture or video container
 */
function addContainer (container) {
    wrapper = container.closest(".wrapper");
    prototype = wrapper.data('prototype');
    index = wrapper.data('index');

    newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    wrapper.data('index', index + 1);

    wrapper.prepend(newForm);
    toggleButton(container);
}

/**
 * Checks if the textArea value matches with an iFrame and add it to show
 */
function displayIFrame (textArea) {
    iframeVal = $(textArea).val();
    iframeDiv = $(textArea).parent().next().find(".iFrame");

    if (iframeVal.indexOf("<iframe") !== -1 && iframeVal.indexOf("</iframe>") !== -1) {
        iframeDiv.attr("class", "embed-responsive embed-responsive-16by9 iFrame");
        iframeDiv.html(iframeVal);
    }else {
        iframeDiv.parent().attr("class", "col-6 col-lg-4 mx-auto video-not-displaying");
        iframeDiv.attr("class", "iFrame");
        iframeDiv.html('<small style="color: darkred" class="text-center">Cet iFrame n\'est pas valide</small>');
    }
}

function setGroupField(container) {
    wrapper = container.closest(".wrapper");
    prototype = wrapper.data('prototype');
    index = wrapper.data('index');

    newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    wrapper.data('index', index + 1);
    newInput = $(newForm).find("input");
    wrapper.prepend(newInput);

    if (container.has("input").length >= 1) {
        createGroup();
        $("fieldset", wrapper).remove();
    }
    return newInput;
}

/**
 * Toggle picture's and video's control buttons when adding and removing the containers
 */
function toggleButton (container) {
    wrapper = container.parent(".wrapper");

    plusSquares = wrapper.find(".fa-plus-square");
    trashSquares = wrapper.find(".fa-trash");

    plusSquares.hide().first().show();
    trashSquares.show().first().hide();
}

/**
 * Set the position of the video info after the first video label
 */
function setPositionVideoInfo () {
    videoInfo = $("#video-info");
    label = $(".video-container div label").first();
    label.after(videoInfo);
}

/**
 *
 * Create field for CollectionType when empty (creation of a entity)
 */
function setFirstCollectionField (container){
    elem = container.attr('class');
    if (elem.indexOf("picture-container") !== -1) {
        if (container.find("input").length === 0) {
            addContainer(container);
            container.remove();
        }
    }
    else if (elem.indexOf("video-container") !== -1) {
        if (container.find("textarea").length === 0) {
            addContainer(container);
            container.remove();
        }
    }
}

/**
 * Get value from input#create-group and create a new checkbox
 */
function createGroup (){
    input = $("#create-group input");

    input.each(function () {
        groupName = $(this).val().trim();
        if (groupName.length === 0){
            return;
        }
        $(this).val("");

        //  First letter uppercase for each words
        groupName = groupName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });

        if ($("#create_trick_groups").length !== 0) {
            formsCheck = $("#create_trick_groups").find(".form-check");
        }else if ($("#update_trick_groups").length !== 0) {
            formsCheck = $("#update_trick_groups").find(".form-check");
        }
        newIndex = (formsCheck.length).toString();
        newCheckbox = formsCheck.first().clone();

        // Replace the id of the input field with the new index
        attrClass = $("input", newCheckbox).attr("class");
        attrId = 'create_group_checkbox' + newIndex + 1;

        // Set attributes
        $("input", newCheckbox).attr({
            id: attrId,
            class: attrClass + ' create_group_checkbox',
            checked: true,
            value: groupName
        });
        // $("input", newCheckbox).removeAttr("value");
        $("label", newCheckbox).attr("for", attrId);
        $("label", newCheckbox).text(groupName);

        helper = $("#create-group-help").show();

        formsCheck.parent().append(newCheckbox).append(helper);
    });
}

/**
 * Display Error Div
 */
function displayAlert(errors) {
    alertDiv = $("#field-warning");

    $('html, body').animate({scrollTop: alertDiv.offset().top}, 500);

    alertDiv.text("");
    $.each(errors, function () {
        alertDiv.append("<strong>" + this + "</strong></br>");
    });

    alertDiv.fadeIn(400);

    setTimeout(function () {
            alertDiv.fadeOut(400);
        },5000
    );
}

/**
 * Display a message when required field(s) are empty on submit
 */
function checkRequiredField() {
    requiredFields = $("*[required=required]");
    alertDiv = $("#field-warning");
    errors = [];

    requiredFields.each(function () {
        if ( "" === $(this).val() ) {
            switch ( $(this).attr("id") ) {
                case "create_trick_title": errors.push("Le titre est requis."); break;
                case "create_trick_description": errors.push("Une description de la figure est requise."); break;
                case "create_trick_mainPicture_file": errors.push("L'image principale est obligatoire."); break;
            }
        }
    });

    if (errors.length > 0) {
        displayAlert(errors);
        return false;
    }
    return true;
}

/**
 * Symfony interprets empty input value as given value to treat with CollectionTypes
 * This function remove the inputs with empty values before submit
 */
function removeEmptyFields () {
    newGroupInput = $("#create-group input");
    videoContainer = $(".video-container");

    newGroupInput.each(function () {
        if ( $(this).val() === "" ) {
            $(this).remove();
        }
    });

    videoContainer.each(function () {
        iFrameTextarea = $(this).find("textarea");

        iFrameVal = iFrameTextarea.val();
        if (iFrameVal === "") {
            this.remove();
        }
    });
}

/**
 * Remove the checkbox added to groups and add values to the createGroup input
 */
function sendDataNewGroups() {
    createGroupCheckbox = $(".create_group_checkbox");
    createGroupInput = $("#create-group input");

    createGroupCheckbox.each(function () {
        if ($(this).is(":checked")) {
            newInput = setGroupField($(".group-container"));
            $(newInput).val($(this).val()).attr("type", "hidden");
            createGroupCheckbox.parent().remove();
        }
    });
}

/**
 * Change the img label
 */
$(document).on("change", ":input[type=file]", function () {
    input = this;

    if (input.files && input.files[0]) {
        reader = new FileReader();
    }
    reader.onload = function (e) {

        $(input.closest(".picture-load")).find("label img")
            .attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
    pictureContainerHeight();

    if (hiddenInput = $(input).closest(".input-file").find("input[type=hidden]")) {
        hiddenInput.val(false);
    }
});

/**
 * Append addPicture and addVideo Element
 */
$(document).on("click", ".fa-plus-square", function (e) {
    e.preventDefault();

    container = getContainers(this);

    addContainer(container);
    pictureContainerHeight();

    setPositionVideoInfo();
});

/**
 * Remove addPicture and AddVideo Element
 */
$(document).on("click", ".fa-trash", function (e) {
    e.preventDefault();

    container = getContainers(this);
    containers = container.parent(".wrapper").children();

    $(container).remove();
    toggleButton(containers);
});

// Click on create group button Event
$(document).on("click", "#group-create-btn", function () {
    createGroup();
});

// Enter keyboard on create group field, return false disable form submit
$(document).on("keypress", "#create-group input", function ( event ) {
    if ( event.keyCode === 13 ) {
        event.preventDefault();
        createGroup();
        return false;
    }
});

// Before submit event
$(document).on("click", ":submit", function () {
    isSubmitable = checkRequiredField();
    if (isSubmitable){
        sendDataNewGroups();
    }
});

// On submit event
$("form").submit(function () {
    removeEmptyFields();
});

$('#video-info').popover({
    trigger: 'focus'
});

$(document).on("change", ".iframe-textarea", function () {
    displayIFrame(this);
});

$(document).ready(function() {
    pictureContainerHeight();

    pictureContainer = $(".picture-container");
    videoContainer = $(".video-container");
    groupContainer = $(".group-container");

    toggleButton(pictureContainer);
    toggleButton(videoContainer);

    setFirstCollectionField(pictureContainer);
    setFirstCollectionField(videoContainer);
    setGroupField(groupContainer);
    setPositionVideoInfo();
});

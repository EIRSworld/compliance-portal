function getParentByTagName(obj, tagName) {
    tagName = tagName.toLowerCase();
    while (obj!= null && obj.tagName!=null && obj.tagName.toLowerCase() !==
    tagName) {
        obj=obj.parentNode;
    }
    return obj;
}

/*Not working*/
setInterval(function(){
    var list = document.getElementsByClassName("custom-bg-green1");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-green');
        // list[i].parentElement.parentElement.parentElement.parentElement.classList.add('custom-bg-red');
    }
    var list = document.getElementsByClassName("custom-bg-yellow");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-yellow');
    }
    var list = document.getElementsByClassName("custom-bg-red");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-red');
    }
    var list = document.getElementsByClassName("custom-bg-blue");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-blue');
    }
    var list = document.getElementsByClassName("custom-bg-red-event");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-red-event');
    }
}, 1000);




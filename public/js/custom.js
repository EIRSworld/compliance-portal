function getParentByTagName(obj, tagName) {
    tagName = tagName.toLowerCase();
    while (obj!= null && obj.tagName!=null && obj.tagName.toLowerCase() !==
    tagName) {
        obj=obj.parentNode;
    }
    return obj;
}

setInterval(function(){
    var list = document.getElementsByClassName("custom-bg-green");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-green');
        // list[i].parentElement.parentElement.parentElement.parentElement.classList.add('custom-bg-red');
    }
    var list = document.getElementsByClassName("custom-bg-yellow");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-yellow');
        // list[i].parentElement.parentElement.parentElement.parentElement.classList.add('custom-bg-red');
    }
    var list = document.getElementsByClassName("custom-bg-red");
    for (var i=0; i<list.length; i++) {
        getParentByTagName(list[i],"tr").classList.add('custom-bg-red');
        // list[i].parentElement.parentElement.parentElement.parentElement.classList.add('custom-bg-red');
    }
}, 1000);




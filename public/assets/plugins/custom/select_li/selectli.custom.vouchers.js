var liSelected;

var index = -1;
document.addEventListener('keydown', function (event) {

    if (!ul.innerHTML) {

        return;
    }

    var len = ul.getElementsByTagName('a').length - 1;

    if (event.which === 40) {

        // ul.scrollTop += 22;
        document.getElementById('search_product').value = '';
        index++;
        // Down
        if (liSelected) {

            removeClass(liSelected, selectObjClassName);
            next = ul.getElementsByTagName('a')[index];
            if (typeof next !== undefined && index <= len) {

                liSelected = next;
            } else {

                index = 0;
                liSelected = ul.getElementsByTagName('a')[0];
            }

            addClass(liSelected, selectObjClassName);
        } else {

            index = 0;
            liSelected = ul.getElementsByTagName('a')[0];
            addClass(liSelected, selectObjClassName);
        }
    } else if (event.which === 38) {
        //up
        // ul.scrollTop -= 22;
        if (liSelected) {

            removeClass(liSelected, selectObjClassName);
            index--;
            next = ul.getElementsByTagName('a')[index];

            if (typeof next !== undefined && index >= 0) {

                liSelected = next;
            } else {

                index = len;
                liSelected = ul.getElementsByTagName('a')[len];
            }

            addClass(liSelected, selectObjClassName);
        } else {

            index = 0;
            liSelected = ul.getElementsByTagName('a')[len];
            addClass(liSelected, selectObjClassName);
        }
    }
}, false);

function removeClass(el, className) {

    if (el.classList) {

        el.classList.remove(className);
    } else {

        el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
    }
};

function addClass(el, className) {

    if (el.classList) {

        el.classList.add(className);
    } else {

        el.className += ' ' + className;
    }
};

var trSelected;

var index = -1;
document.addEventListener('keydown', function (event) {

    if (!tbody.innerHTML) {

        return;
    }
    
    var len = tbody.getElementsByTagName('tr').length - 1;

    if (event.which === 40) {

        document.getElementById('search_product').value = '';
        index++;
        //down 
        if (trSelected) {

            removeClass(trSelected, trSelectObjClassName);
            next = tbody.getElementsByTagName('tr')[index];
            if (typeof next !== undefined && index <= len) {

                trSelected = next;
            } else {

                index = 0;
                trSelected = tbody.getElementsByTagName('tr')[0];
            }

            addClass(trSelected, trSelectObjClassName);
        } else {

            index = 0;
            trSelected = tbody.getElementsByTagName('tr')[0];
            addClass(trSelected, trSelectObjClassName);
        }
    } else if (event.which === 38) {
        //up
        if (trSelected) {

            removeClass(trSelected, trSelectObjClassName);
            index--;
            next = tbody.getElementsByTagName('tr')[index];

            if (typeof next !== undefined && index >= 0) {

                trSelected = next;
            } else {

                index = len;
                trSelected = tbody.getElementsByTagName('tr')[len];
            }

            addClass(trSelected, trSelectObjClassName);
        } else {

            index = 0;
            trSelected = tbody.getElementsByTagName('tr')[len];
            addClass(trSelected, trSelectObjClassName);
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

    className.focus;
    
    if (el.classList) {

        el.classList.add(className);
    } else {

        el.className += ' ' + className;
    }
};
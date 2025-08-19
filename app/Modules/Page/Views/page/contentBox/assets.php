<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Uploads</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=""> 
    <style>
        body {
            font-family:sans-serif;
            font-weight: 300;
            font-size:13px;
            color:#111;
        }
        #files {
            opacity:0;
            margin-top:30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            align-items: center;
        }
        #files label {margin:20px;position:relative;width:150px;height:150px;display:flex;align-items:center;justify-content:center;flex-direction: column;box-sizing:border-box;text-decoration:none;color:#111;cursor: pointer;}
        
        /* images */
        #files label.image img {max-height:150px;max-width:150px;}
        #files label.image input {
            visibility:hidden;
            position:absolute;bottom:0;right:0;
        }
        .edit #files label.image input {
            visibility: visible;
        }

        /* videos */
        #files label.video svg {
            width:50px;
            height:50px;
            fill:#111;
            margin-bottom:8px;
        }
        #files label.video input {
            margin-top:10px;
            visibility:hidden;
        }
        .edit #files label.video input {
            visibility:visible;
        }

        /* lottie */
        #files label.lottie svg {
            width:50px;
            height:50px;
            fill:#111;
            margin-bottom:8px;
        }
        #files label.lottie input {
            margin-top:10px;
            visibility:hidden;
        }
        .edit #files label.lottie input {
            visibility:visible;
        }

        /* controls */
        .cmd-edit {
            display:block;
        }
        .cmd-cancel,
        .cmd-delete {
            display:none;
        }
        .edit .cmd-edit {
            display:none;
        }
        .edit .cmd-cancel,
        .edit .cmd-delete {
            display:block;
        }

        button > * {
            pointer-events: none;
            /* https://css-tricks.com/slightly-careful-sub-elements-clickable-things/ */ }
        
        button {
            width: auto;
            padding: 0 20px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.95);
            color: #111111;
            font-family: sans-serif;
            font-size: 12px;
            letter-spacing: 1px;
            font-weight: 300;
            border: transparent 1px solid;
            box-shadow: 0px 3px 6px -6px rgba(0, 0, 0, 0.32);
            opacity: 1;
            line-height: 1;
            display: inline-block;
            box-sizing: border-box;
            margin: 0;
            cursor: pointer;
            text-transform: none;
            text-align: center;
            position: relative;
            border-radius: 0;
            user-select: none;
            -moz-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -o-user-select: none;
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center; }

            input:focus,
            button:focus,
            textarea:focus,
            select:focus {
                outline: none; }
            
            .panel-edit {
                position:fixed;
                right:0;
                top:5px;
                width:auto;
                display:flex;
                height: 36px;
                z-index:1;
            }
    </style>
</head>
<body>
    
<svg width="0" height="0" style="position:absolute;display:none;">
    <defs>
        <symbol viewBox="0 0 512 512" id="ion-ios-film-outline"><path d="M56 88v336h400V88H56zm72 320H72v-48h56v48zm0-64H72v-48h56v48zm0-64H72v-48h56v48zm0-64H72v-48h56v48zm0-64H72v-48h56v48zm240 256H144V264h224v144zm0-160H144V104h224v144zm72 160h-56v-48h56v48zm0-64h-56v-48h56v48zm0-64h-56v-48h56v48zm0-64h-56v-48h56v48zm0-64h-56v-48h56v48z"></path></symbol>
        <symbol viewBox="0 0 512 512" id="ion-volume-medium">
            <path d="M270 407.7V104.4L175.3 192H71v128h104.3zm56.3-52.1c20.5-27.8 32.8-62.3 32.8-99.6 0-37.4-12.3-71.8-32.8-99.6l-20.4 15.3c17.4 23.6 27.8 52.7 27.8 84.3 0 31.6-10.4 60.7-27.8 84.3l20.4 15.3zm66.5 46c30-40.7 48-91 48-145.6s-18-104.9-48-145.6l-20.4 15.3c26.9 36.4 43 81.4 43 130.3 0 48.9-16.1 93.8-43 130.3l20.4 15.3z"/>
        </symbol>
    </defs>
</svg>
<form method="post">
    <div class="panel-edit">
        <button class="cmd-edit">Edit</button>
        <button class="cmd-delete" type="submit">Delete Selected</button>
        <button class="cmd-cancel">Cancel</button>
    </div>
    <?php $path = FCPATH.'uploads/cBox/'; ?>
    <div id="files">
        <?php getFiles($path); ?>
    </div>
</form>

<script>
    document.querySelector('#files').style.opacity = '1';

    var images = document.querySelectorAll('#files img');
    [].forEach.call(images, function(img) {
        
        var imgurl = img.getAttribute('src');
        img.addEventListener((isTouchSupport()?'touchstart':'click'), function(e){

            if(!hasClass(document.body, 'edit')) {

                var doc = document.documentElement;
                var oldScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                window.setTimeout( function() {
                    var newScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                    if (Math.abs(oldScrollTop-newScrollTop)<3) {

                        /* 
                        USE THIS FUNCTION TO SELECT CUSTOM ASSET WITH CUSTOM VALUE TO RETURN 
                        An asset can be a file, an image or a page in your own CMS
                        */
                        
                        parent.selectAsset(imgurl);
                        parent.focus(); // so that document.click on parent works without have to click to focus

                    }
                }, 200);

                e.stopPropagation();
                e.preventDefault();
            }

            // e.stopPropagation();
            // e.preventDefault();
            
        });

    });

    var links = document.querySelectorAll('#files label.video');
    [].forEach.call(links, function(link) {
        
        var fileurl = link.getAttribute('data-href');
        link.addEventListener((isTouchSupport()?'touchstart':'click'), function(e){

            if(!hasClass(document.body, 'edit')) {

                // https://stackoverflow.com/questions/7069458/prevent-touchstart-when-swiping
                var doc = document.documentElement;
                var oldScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                window.setTimeout( function() {
                    var newScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                    if (Math.abs(oldScrollTop-newScrollTop)<3) {

                        /* 
                        USE THIS FUNCTION TO SELECT CUSTOM ASSET WITH CUSTOM VALUE TO RETURN 
                        An asset can be a file, an image or a page in your own CMS
                        */

                        parent.selectAsset(fileurl);
                        parent.focus(); // so that document.click on parent works without have to click to focus

                    }
                }, 200);
                
                e.stopPropagation();
                e.preventDefault();
            }

            // e.stopPropagation();
            // e.preventDefault();

        });

    });

    var lotties = document.querySelectorAll('#files label.lottie');
    [].forEach.call(lotties, function(link) {

        var fileurl = link.getAttribute('data-href');
        link.addEventListener((isTouchSupport()?'touchstart':'click'), function(e){

            if(!hasClass(document.body, 'edit')) {

                // https://stackoverflow.com/questions/7069458/prevent-touchstart-when-swiping
                var doc = document.documentElement;
                var oldScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                window.setTimeout( function() {
                    var newScrollTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
                    if (Math.abs(oldScrollTop-newScrollTop)<3) {

                        /* 
                        USE THIS FUNCTION TO SELECT CUSTOM ASSET WITH CUSTOM VALUE TO RETURN 
                        An asset can be a file, an image or a page in your own CMS
                        */
    
                        parent.selectAsset(fileurl);
                        parent.focus(); // so that document.click on parent works without have to click to focus

                    }
                }, 200);
                
                e.stopPropagation();
                e.preventDefault();
            }
            
            // e.stopPropagation();
            // e.preventDefault();

        });

    });

    var btnEdit = document.querySelector('.cmd-edit');
    btnEdit.addEventListener((isTouchSupport()?'touchstart':'click'), function(e){
        // var div = document.querySelector('#files');

        let elms = document.querySelectorAll('input[type="checkbox"]');
        elms.forEach(function(elm){
            elm.checked = false;
        });

        addClass(document.body, 'edit');
        e.preventDefault();
    });

    var btnCancel = document.querySelector('.cmd-cancel');
    btnCancel.addEventListener((isTouchSupport()?'touchstart':'click'), function(e){
        // var div = document.querySelector('#files');
        removeClass(document.body, 'edit');
        e.preventDefault();
    });

    function isTouchSupport() {
        if (navigator.userAgent.match(/Mac/) && navigator.maxTouchPoints && navigator.maxTouchPoints > 2) {
            return true;
        } else {
            return false;
        }
    }

    function hasClass(element, classname) {
        if(!element) return false;
        try{
            var s = element.getAttribute('class');
            return new RegExp('\\b'+ classname+'\\b').test(s);
        } catch(e) { 
            // Do nothing
        }
    }

    function addClass(element, classname) {

        if(!element) return;

        // https://stackoverflow.com/questions/37943006/unable-to-change-class-name-of-svg-element
        if (element instanceof SVGElement) {
            var SVGAnimatedString = function () {};
            if (typeof window !== 'undefined1') {
                SVGAnimatedString = window.SVGAnimatedString;
            }
            
            const newClasses = this.convertToArray(classname);
            var classList;
            if (element.className instanceof SVGAnimatedString) {
                classList = this.convertToArray(element.className.baseVal);
            } else {
                classList = this.convertToArray(element.className);
            }
            newClasses.forEach((newClass) => {
                if (classList.indexOf(newClass) === -1) {
                    classList.push(newClass);
                }
            });
            element.setAttribute('class', classList.join(' '));
            return;
        }

        if(this.hasClass(element,classname)) return;
        if(element.classList.length===0) element.className = classname;
        else element.className = element.className + ' ' + classname;
        element.className = element.className.replace(/  +/g, ' ');
    }

    function removeClass(element, classname) {

        if(!element) return;

        // https://stackoverflow.com/questions/37943006/unable-to-change-class-name-of-svg-element
        if (element instanceof SVGElement) {
            var SVGAnimatedString = function () {};
            if (typeof window !== 'undefined2') {
                SVGAnimatedString = window.SVGAnimatedString;
            }
            
            const newClasses = this.convertToArray(classname);
            var classList;
            if (element.className instanceof SVGAnimatedString) {
                classList = this.convertToArray(element.className.baseVal);
            } else {
                classList = this.convertToArray(element.className);
            }
            newClasses.forEach((newClass) => {
                const index = classList.indexOf(newClass);
                if (index !== -1) {
                    classList.splice(index, 1);
                }
            });
            element.setAttribute('class', classList.join(' '));
            return;
        }

        if(!element) return;
        if(element.classList.length>0) {
            
            var i, j, imax, jmax;
            var classesToDel = classname.split(' ');
            for (i=0, imax=classesToDel.length; i<imax; ++i) {
                if (!classesToDel[i]) continue;
                var classtoDel = classesToDel[i];

                var sClassName = ''; 
                var currentClasses = element.className.split(' ');
                for (j=0, jmax=currentClasses.length; j<jmax; ++j) {
                    if (!currentClasses[j]) continue;
                    if (currentClasses[j]!==classtoDel) sClassName += currentClasses[j] + ' ';
                }
                element.className = sClassName.trim();
            }

            if(element.className==='') element.removeAttribute('class');

        }
    }

</script>
</body>
</html>
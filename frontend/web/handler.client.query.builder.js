/* 
 * handler.client.query.builder
 * version 0.2
 * connector version 0.1 
 */


function buildRequest(method, type, args) {
    return {
        'type': type,
        'method': method,
        'args': args
    };
}

function buildRequest(method, type, args, callback) {
    return {
        'type': type,
        'method': method,
        'args': args,
        'callback': callback
    };
}

function buildClassRequest(className, type, method, args) {
    return {
        'type': type,
        'class': className,
        'method': method,
        'args': args
    };
}

function buildConstructClassRequest(className, initArgs, type, method, args, callback) {
    return {
        'type': type,
        'method': method,
        'args': args,
        'callback': callback,
        'initArgs': initArgs,
        'class': className
    };
}

function buildClassRequest(className, type, method, args, callback) {
    return {
        'type': type,
        'class': className,
        'method': method,
        'args': args,
        'callback': callback
    };
}


function initDefConfig(definition, initArgs) {
    return {
        'definition': definition,
        'initArgs': initArgs
    };
}

//function auth(method) {
//
//    return {
//        'authMethod': method
//    };
//}
function auth(method, token) {
    if (token == null) {
        return {
            'authMethod': method
        };
    } else {
        return {
            'authMethod': method,
            'accessToken': token
        };
    }

}

function buildAction(defConfig, requests) {
    return extObj(defConfig, {'requests': requests});
}

function extObj(a, b) {
    var c = {};
    for (key in a) {
        c[key] = a[key];
    }
    for (key in b) {
        c[key] = b[key];
    }
    return c;
}
function buildHandler(auth, action) {
    return extObj(auth, {
        '__action__': JSON.stringify(action)
    });
}

function buildPostRequest(data) {
    return serialize(data);
}

function count(obj) {
    var count = 0;
    for (var prs in obj)
    {
        if (obj.hasOwnProperty(prs))
            count++;
    }
    return count;
}


function serialize(object) {
    countObj = count(object);
    var iter = 0;
    var s = "";
    var elem = "";
    for (i in object) {
        iter++;
        if (iter < countObj) {
            elem = "&";
        } else {
            elem = "";
        }
        s += i + "=" + object[i] + elem;
    }
    return s;
}

function handlerQuery(addr, query) {
    var handler = new XMLHttpRequest();
    var answer = {};
    handler.open("POST", addr, false);
    handler.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    handler.send(query);

//    handler.onreadystatechange = function () { // (3)
//        if (handler.readyState != 4)
//            return;
    if (handler.status != 200) {
        return {"state": "error"};
    } else {
        answer = JSON.parse(handler.responseText);
    }

//    };
    //console.log(query);
    return answer;
}



/* Дальше понос */



function setAvatar(id) {
    var requests = [];
    requests.push(buildClassRequest('HUser', 'default', 'setPhoto', {
        'id': id,
        'type': 'id'
    }, 'actState'));
    //console.log(requests);
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    //console.log('answer: ' + ans);
    //console.log(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests)));
    if (ans.actState == true) {
        //document.getElementById("file-" + id).style.display = "none";
        window.location.href = "http://forum.postgen.xyz/index.php?r=site%2Faccountedit";
    }
}

function setBackground(id) {
    var requests = [];
    requests.push(buildClassRequest('HUser', 'default', 'setBackground', {
        'id': id,
        'type': 'id'
    }, 'actState'));
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    if (ans.actState.state == true) {
        //document.getElementById("file-" + id).style.display = "none";
        document.body.style.backgroundImage = "url(" + ans.actState.url + ")";
        // window.location.href = "http://forum.postgen.xyz/";
    }
}


function deleteFileById(id) {
    var requests = [];
    requests.push(buildClassRequest('HFile', 'default', 'delete', {
        'id': id,
        'type': 'id'
    }, 'deleteStatus'));

    //console.log(requests);
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    //console.log('answer: ' + ans);
    //console.log(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests)));
    if (ans.deleteStatus == true) {
        document.getElementById("file-" + id).style.display = "none";
        //location.reload();
    }
}

function joinToGroup(id) {
    var requests = [];
//    requests.push(buildClassRequest('HGroup', 'default', 'leftsideJoin', {
//        'id': id
//    }, 'status'));
    requests.push(buildConstructClassRequest('HGroup', {'id': id}, 'default', 'leftsideJoin', {
        'id': id
    }, 'status'));
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    if (ans.status == true) {
        //action if success entered in group
    }
}

function setGroupPhoto(fileId, groupId) {
    var requests = [];
    requests.push(buildClassRequest('HGroup', 'default', 'setPhoto', {
        'group': groupId,
        'fileId': fileId
    }, 'actState'));
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    if (ans.actState == true) {
        window.location.href = "http://forum.postgen.xyz/index.php?r=site%2Fgroup&groupId=" + groupId;
    }
}

function editGroupInfo(groupId) {
    var requests = [];
    requests.push(buildClassRequest('HGroup', 'default', 'edit', {
        'group': groupId,
        'name': document.getElementById('name').value,
        'title': document.getElementById('title').value,
        'description': document.getElementById('description').value
    }, 'actState'));
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    if (ans.actState == true) {
        window.location.href = "http://forum.postgen.xyz/index.php?r=site%2Fgroup&groupId=" + groupId;
    }
}

function rate(noteId, rate) {
    var requests = [];
    requests.push(buildClassRequest('HNote', 'default', 'rate', {
        'noteId': noteId,
        'rate': rate
    }, 'actState'));
    ans = handlerQuery("http://forum.postgen.xyz/index.php?r=site/handler", buildPostRequest(buildHandler(auth('cookie'), buildAction(initDefConfig(), requests))));
    if (ans.actState) {
        if (rate){
        document.getElementById('note-rate-' + noteId).innerHTML = (Number(document.getElementById('note-rate-' + noteId).innerHTML) + 1) + "";
    } else {
        document.getElementById('note-rate-' + noteId).innerHTML = (Number(document.getElementById('note-rate-' + noteId).innerHTML) - 1) + "";
    }
    }
}
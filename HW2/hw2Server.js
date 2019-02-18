var http = require('http');
var express = require('express');
var url = require('url');
var bodyParser = require('body-parser');
var cookieParser = require('cookie-parser');

var app = express();

app.use(bodyParser.urlencoded({extended:true}));
app.use(bodyParser.json());
app.use(cookieParser());

const hostname = '159.65.109.104';
const port = 8084;
var server = app.listen(process.env.PORT||port);

var docType = '<!DOCTYPE HTML>';
var htmlOpenStyle = '<html><head></head><body style = background-color:';
var htmlOpen = '<html><head></head><body>';
var htmlClose = '</body></html>';

//html files placed in files directory will be accessible 
app.use(express.static('files'));

//for helloWorld
function getRandomColor(){
    var randNumber = Math.floor(Math.random()*3);  //get number from 0 to 9 
    if (randNumber == 0){
        return "blue";
    }
    else if (randNumber == 1){
        return "yellow";
    }
    else{
        return "white";
    }
}

app.get('/',function(req,res){
        res.send("home");
});

app.get('/helloWorld',function (req,res){
    var backgroundColor = getRandomColor();
    res.setHeader('Content-Type', 'text/html');
    res.send(`${docType} ${htmlOpenStyle+backgroundColor+'>'} <p>Hello Web World from JavaScript on ${Date()}. Enjoy my ${backgroundColor} page!</p> ${htmlClose}`);
});

app.get('/helloData', function(req,res){

    var params = url.parse(req.url,true).query;
    var responseBody = null; 
    console.log(params);
    if (params['response'] == 'XML'){
        res.setHeader('Content-Type', 'text/xml');
        console.log('XML!');
        responseBody = `<?xml version='1.0' encoding='UTF-8'?>\n<msg>Hello Data it's ${Date()}</msg>`;
    }
    else if (params['response'] == 'JSON'){
        res.setHeader('Content-Type', 'application/json');
        console.log('JSON!');
        responseBody = `{\n\t"msg" : "Hello Data it's ${Date()}"\n}`;
    }
    else{
        res.setHeader('Content-Type', 'text/html');
        responseBody = `${docType}${htmlOpen}<h1>Error:Specify response parameter</h1>${htmlClose}`;
        console.log('NEITHER');
    }
    res.send(`${responseBody}`);

});


function envVarsToString(varList){
    var listDOM = '';
    for (var key in varList){
        listDOM += `<p>${key}: ${varList[key]}</p>`;
    }
    return listDOM
}

app.get('/envEcho', function(req,res){
    res.setHeader('Content-Type', 'text/html');
    var content = null;
    //TODO might not need process.env???
    content = envVarsToString(req.headers) + envVarsToString(process.env);
    res.send(`${docType} ${htmlOpen} ${content} ${htmlClose}`);

});


//get request for echo
app.get('/echo',function(req,res){
    res.setHeader('Content-Type', 'text/html');
    var content = null;
    var params = url.parse(req.url,true).query;
    content = `${params['color']}><p>Hello ${params['first']}${params['last']} from a Web app written in JavaScript on ${Date()}</p>`; 
    res.send(`${docType}${htmlOpenStyle}${content}${htmlClose}`);
});

//post request for echo
app.post('/echo',function(req,res){
    res.setHeader('Content-Type', 'text/html');
    var content = null;
    //var params = url.parse(req.url,true).query;
    content = `${req.body.color}><p>Hello ${req.body.first}${req.body.last} from a Web app written in JavaScript on ${Date()}</p>`; 
    res.send(`${docType}${htmlOpenStyle}${content}${htmlClose}`);

});

app.all('/sessionpage1', function(req,res){
    res.setHeader('Content-Type','text/html');
    var content = '<form action = "/sessionpage1" method="post"><label>User Name: <input type="text" name="userName"></label>\n<input type="submit" value="Submit"></form><a href="/sessionpage2">Click here for sessionpage2</a>';
    //set cookie data
    //if (res.cookie == {} || res.cookie['User-name'] == 'undefined'){
    if (req.method == 'POST'){
        res.cookie('User-name', req.body.userName);
    }
    //}
    res.send(`${docType}${htmlOpen}${content}${htmlClose}`);
});

app.all('/sessionpage2',function(req,res){
    res.setHeader('Content-Type','text/html');
    var textCont = null;
    
    // message when no cookie is found
    if (req.cookies['User-name']== undefined || req.cookies['User-name'] == ''){
        textCont = "<p>Howdy stranger. Please tell me your name on page 1!</p>";
    }
    
    //message when cookie is found
    else{
        textCont = `Hi ${req.cookies['User-name']}, nice to meet you!`;
    }
    var content = '<form action = "/clearCookie" method="post"><input type="submit" name="clearCookie" value="Clear"></form>';
    res.send(`${docType}${htmlOpen}${textCont}${content}${htmlClose}`);
     
});

app.post('/clearCookie',function(req,res){
     //clears cookies
    if (req.body != {} && req.body.clearCookie == "Clear"){
        res.clearCookie('User-name');
    }
    var content = '<a href="/sessionpage1">To sessionpage1</a> <a href="/sessionpage2"> To sessionpage2 </a>'
    res.send(`${docType}${htmlOpen}${content}${htmlClose}`);
});


var accList = {};
app.route('/CRUD')
    //create
    .post(function(req,res){
        var newUser = {fullname: req.body.fullname, login: req.body.login, admin: req.body.admin};
        accList[newUser.login] = newUser;
        console.log(accList);
        res.setHeader('Content-Type', 'application/json');
        res.send(`${JSON.stringify(accList[req.body.login])}`);
    })
   
    //query by login 
    //read
    .get(function (req,res){
        console.log(accList[req.query.login]);
        res.setHeader('Content-Type', 'application/json');
        res.send(`${JSON.stringify(accList[req.query.login])}`);
    })
    //update
    //needs updatingUser argument to specify which user is being updated. The rest of the arguments are optional- if not specified, the original value will be preserved. Sent through message body
    .put(function (req,res){
        var updatingUser = req.body.updatingUser;
        var newFullName = req.body.fullname || accList[updatingUser].fullname;
        var newLogin = req.body.login || accList[updatingUser].login;
        var newAdmin = req.body.admin || accList[updatingUser].admin;
        var updatedUser = {fullname: newFullName, login: newLogin, admin: newAdmin};
        delete accList[updatingUser];
        accList[newLogin] = updatedUser;
        console.log(accList);
        res.setHeader('Content-Type', 'application/json');
        res.send(`${JSON.stringify(accList[newLogin])}`);
    })
    //delete
    //deleting only requires specifying the login. Sent through querystring
    .delete(function (req,res){
        delete accList[req.body.login];
        console.log(accList);
        res.setHeader('Content-Type', 'application/json');
        res.send(`${JSON.stringify(accList)}`);
    });

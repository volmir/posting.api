# posting.api
REST API application


***API v1.0***

**JSON and XML responce**

```
curl -i -X GET http://posting.local/api/v1 --header 'Accept: application/json'
curl -i -X GET http://posting.local/api/v1 --header 'Accept: application/xml'
```

**Authentification**

```
curl -i -X POST http://posting.local/api/v1/company/auth -d '{"username":"company","password":"company"}'
curl -i -X POST http://posting.local/api/v1/specialist/auth -d '{"username":"specialist","password":"specialist"}'
curl -i -X POST http://posting.local/api/v1/client/auth -d '{"username":"client","password":"client"}'
```

**Company**

```
curl -i -X POST http://posting.local/api/v1/company/create -d '{"username":"company837","password":"736239","email":"company@example.com"}'
curl -i -X GET http://posting.local/api/v1/company
```

**Specialist**

```
curl -i -X POST http://posting.local/api/v1/specialist/create -d '{"username":"specialist267","password":"284829","email":"specialist267@example.com","firstname":"John","lastname":"Smith"}'
curl -i -X GET http://posting.local/api/v1/specialist
```

**Specialist**

```
curl -i -X POST http://posting.local/api/v1/client/create -d '{"username":"client865","password":"784832","email":"client865@example.com","firstname":"John","lastname":"Smith"}'
curl -i -X GET http://posting.local/api/v1/client
```

**Categories**

```
curl -i -X GET http://posting.local/api/v1/category
```

**Posts**

```
curl -i -X GET http://posting.local/api/v1/post
curl -i -X GET http://posting.local/api/v1/post/1
curl -i -X POST http://posting.local/api/v1/post -d '{"title":"Post title name","content":"Value of field type must be part of list: seven, three, eight"}'
curl -i -X POST http://posting.local/api/v1/post --data "title=Post title&content=Content text"
curl -i -X PUT http://posting.local/api/v1/post/7 -d '{"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}'
curl -i -X PATCH http://posting.local/api/v1/post/7 -d '{"title":"Some post title"}'
curl -i -X DELETE http://posting.local/api/v1/post/8
```

**Posts (AJAX API URL's call examples)**

```
$.ajax({url: 'http://posting.local/api/v1/post', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/1', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post', method: 'POST', data: {"title":"Another post name","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/7', method: 'PUT', data: {"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/7', method: 'PATCH', data: {title: "Some post title"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/8', method: 'DELETE', dataType: 'json', success: function(response){console.log(response)}})
```


***Access right (RBAC)***

```
php yii rbac/init
php yii roles/assign
php yii roles/revoke
```
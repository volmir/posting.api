# posting.api
REST API application


*API*

**JSON and XML responce**

```
curl -i -X GET http://posting.local/api/v1 --header 'Accept: application/json'
curl -i -X GET http://posting.local/api/v1 --header 'Accept: application/xml'
```

**Authentification**

```
curl -i -X POST http://posting.local/api/v1/auth -d '{"username":"admin","password":"admin"}'
```

**API URL's examples**

```
curl -i -X GET http://posting.local/api/v1/post
curl -i -X GET http://posting.local/api/v1/post/1
curl -i -X POST http://posting.local/api/v1/post -d '{"title":"Post title name","content":"Value of field type must be part of list: seven, three, eight"}'
curl -i -X POST http://posting.local/api/v1/post --data "title=Post title&content=Content text"
curl -i -X PUT http://posting.local/api/v1/post/7 -d '{"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}'
curl -i -X PATCH http://posting.local/api/v1/post/7 -d '{"title":"Some post title"}'
curl -i -X DELETE http://posting.local/api/v1/post/8
```

**AJAX API URL's call examples**

```
$.ajax({url: 'http://posting.local/api/v1/post', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/1', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post', method: 'POST', data: {"title":"Another post name","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/7', method: 'PUT', data: {"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/7', method: 'PATCH', data: {title: "Some post title"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/api/v1/post/8', method: 'DELETE', dataType: 'json', success: function(response){console.log(response)}})
```


*Access right (RBAC)*

```
php yii rbac/init
php yii roles/assign
php yii roles/revoke
```
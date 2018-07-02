# posting.api
REST API application


**JSON and XML responce**

```
curl -i -X GET http://posting.local/v1 --header 'Content-Type: application/json'
curl -i -X GET http://posting.local/v1 --header 'Content-Type: application/xml'
```

**API URL's examples**

```
curl -i -X GET http://posting.local/v1/posts
curl -i -X GET http://posting.local/v1/posts/1
curl -i -X POST http://posting.local/v1/posts -d '{"title":"Post title name","content":"Value of field type must be part of list: seven, three, eight"}'
curl -i -X POST http://posting.local/v1/posts --data "title=Post title&content=Content text"
curl -i -X PUT http://posting.local/v1/posts/7 -d '{"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}'
curl -i -X PATCH http://posting.local/v1/posts/7 -d '{"title":"Some post title"}'
curl -i -X DELETE http://posting.local/v1/posts/8
```

**AJAX API URL's call examples**

```
$.ajax({url: 'http://posting.local/v1/posts', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/v1/posts/1', method: 'GET', dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/v1/posts', method: 'POST', data: {"title":"Another post name","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/v1/posts/7', method: 'PUT', data: {"title":"Another post title","content":"Another value of field type must be part of list: blue, red, green"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/v1/posts/7', method: 'PATCH', data: {title: "Some post title"}, dataType: 'json', success: function(response){console.log(response)}})
$.ajax({url: 'http://posting.local/v1/posts/8', method: 'DELETE', dataType: 'json', success: function(response){console.log(response)}})
```


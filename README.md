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
curl -i -X POST http://posting.local/api/v1/company/create -d '{"username":"company837","password":"736239","email":"company@example.com","country_id":"248","phone":"+380993762379","fullname":"AMT Commpany","address":"Kiev, 01001, Third st. 27, of. 709","description":"Company short description"}'
curl -i -X GET http://posting.local/api/v1/company
curl -i -X GET http://posting.local/api/v1/company/specialist
```

**Specialist**

```
curl -i -X POST http://posting.local/api/v1/specialist/create -d '{"username":"specialist267","password":"284829","email":"specialist267@example.com","firstname":"John","lastname":"Smith","phone":"+380993762379","company_id":"4"}'
curl -i -X GET http://posting.local/api/v1/specialist
```

**Client**

```
curl -i -X POST http://posting.local/api/v1/client/create -d '{"username":"client865","password":"784832","email":"client865@example.com","firstname":"John","lastname":"Smith","phone":"+380993762379","description":"Description"}'
curl -i -X GET http://posting.local/api/v1/client
```

**Uploads**

```
curl --request POST --url http://posting.local/api/v1/upload --header 'Content-Type: application/x-www-form-urlencoded' --header 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW' --form file=undefined
curl --request POST --url http://posting.local/api/v1/upload --header 'Content-Type: application/x-www-form-urlencoded' --header 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW' --form file=undefined --form specialist_id=5
curl -i -X DELETE http://posting.local/api/v1/upload/8
```

**Categories**

```
curl -i -X GET http://posting.local/api/v1/category
curl -i -X POST http://posting.local/api/v1/category -d '{"parent_id":"1","name":"Section 4.7"}'
```

**Services**

```
curl -i -X GET http://posting.local/api/v1/service
curl -i -X GET http://posting.local/api/v1/service/1
curl -i -X POST http://posting.local/api/v1/service -d '{"category_id":"2","price":"12.55","currency_id":"1"}'
curl -i -X PUT http://posting.local/api/v1/service/3 -d '{"price":"18.96","currency_id":"2"}'
curl -i -X PATCH http://posting.local/api/v1/service/3 -d '{"price":"12.55"}'
curl -i -X DELETE http://posting.local/api/v1/service/4
```

**Schedule**

```
curl -i -X GET http://posting.local/api/v1/schedule
curl -i -X GET http://posting.local/api/v1/schedule/2
curl -i -X GET http://posting.local/api/v1/schedule?specialist_id=5
curl -i -X GET http://posting.local/api/v1/schedule?date_from=2018-06-01&date_to=2018-08-01
curl -i -X POST http://posting.local/api/v1/schedule -d '{"specialist_id":"5","date_from":"2018-07-15 14:00:00","date_to":"2018-07-15 15:00:00"}'
curl -i -X DELETE http://posting.local/api/v1/schedule/8
```

**Orders**

```
curl -i -X GET http://posting.local/api/v1/currency
curl -i -X GET http://posting.local/api/v1/order/status
curl -i -X GET http://posting.local/api/v1/order
curl -i -X GET http://posting.local/api/v1/order?client_id=3
curl -i -X GET http://posting.local/api/v1/order?specialist_id=5
curl -i -X GET http://posting.local/api/v1/order?date_from=2018-06-01 00:00:00
curl -i -X GET http://posting.local/api/v1/order?date_to=2018-08-01 23:59:59
curl -i -X GET http://posting.local/api/v1/order?status_client=1
curl -i -X GET http://posting.local/api/v1/order?status_specialist=1
curl -i -X GET http://posting.local/api/v1/order/2
curl -i -X POST http://posting.local/api/v1/order -d '{"schedule_id":"7","client_id":"3","status_client":"1","status_specialist":"1","services":["2","5"]}'
curl -i -X PATCH http://posting.local/api/v1/order/9 -d '{"status_client":"2","status_specialist":"2"}'
curl -i -X DELETE http://posting.local/api/v1/order/6
```

**Order Services**

```
curl -i -X GET http://posting.local/api/v1/orderservice?order_id=11
curl -i -X POST http://posting.local/api/v1/orderservice?order_id=11 -d '{"services":["2","3","5"]}'
curl -i -X DELETE http://posting.local/api/v1/orderservice/12
```

**Session**

```
curl -i -X GET http://posting.local/api/v1/session/4
curl -i -X GET http://posting.local/api/v1/session/4?specialist_id=5
curl -i -X GET http://posting.local/api/v1/session/4?date_from=2018-06-01&date_to=2018-08-01
curl -i -X GET http://posting.local/api/v1/session/4/schedule?date=2018-08-15&specialist_id=5
```

**Comments**

```
curl -i -X GET http://posting.local/api/v1/comment
curl -i -X GET http://posting.local/api/v1/comment?limit=10&offset=20
curl -i -X GET http://posting.local/api/v1/comment/1
curl -i -X POST http://posting.local/api/v1/comment -d '{"specialist_id":"5","company_id":"4","text":"Comment text","rating":"4"}'
curl -i -X PATCH http://posting.local/api/v1/comment/5 -d '{"text":"Comment full text","rating":"5"}'
curl -i -X DELETE http://posting.local/api/v1/comment/4
```

**Promotions**

```
curl -i -X GET http://posting.local/api/v1/promotion
curl -i -X GET http://posting.local/api/v1/promotion/2
curl -i -X POST http://posting.local/api/v1/promotion -d '{"title":"Promotion program","description":"Promotion full description","service_id":"1","price":"28.39","currency_id":"1","discount":"15","date_start":"2018-06-25","date_end":"2018-09-25"}'
curl -i -X PATCH http://posting.local/api/v1/promotion/3 -d '{"title":"Promotion action","description":"Promotion detail description","service_id":"2","price":"36.31","currency_id":"1","discount":"12","date_start":"2018-06-24","date_end":"2018-09-26"}'
curl -i -X DELETE http://posting.local/api/v1/promotion/4
```

**Maillists**

```
curl -i -X GET http://posting.local/api/v1/maillist
curl -i -X GET http://posting.local/api/v1/maillist/2
curl -i -X POST http://posting.local/api/v1/maillist -d '{"title":"Email maillist","text":"Maillist full description","type":"1"}'
curl -i -X PATCH http://posting.local/api/v1/maillist/3 -d '{"title":"SMS maillist","text":"Maillist detail description"}'
curl -i -X DELETE http://posting.local/api/v1/maillist/4
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

***Access right (RBAC)***

```
php yii rbac/init
php yii roles/assign
php yii roles/revoke
```
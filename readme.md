# imgboard_server
----------
Backend to my Imgboard Project: [Imgboard Angular](https://github.com/yannikVole/angular_imgboard)


## Routes
----------
### Users
```
 - /users [GET] get all
 - /users [POST] add new
 - /users/{id} [GET] get one by id
 - /users/{id} [PUT] update one by id
 - /users/{id} [DELETE] delete one by id
 - /users/{id}/threads [GET] get threads by user_id
```
----------

### Threads
```
 - /threads [GET]
 - /threads [POST]
 - /threads/{id} [GET]
 - /threads/{id} [PUT]
 - /threads/{id} [DELETE]
 - /threads/{id}/comments [POST]
```

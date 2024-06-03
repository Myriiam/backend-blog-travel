
#  Travel Bloggers - API (Backend)

Here you can find all endpoints related to the website [travel Bloggers](https://vanyl.github.io/travelBloggers-frontend/#/). This is actually a Full-Stack group project (blog) about tips and ideas for travel lovers.

# Technologies
 - PHP framework : **Laravel** (with **Composer**)
 - Database : **PostgreSQL**
 - This database is deployed on [Heroku](https://signup.heroku.com/login)

# List of endpoints

| Endpoint | Method  | Action | Member only | 
|--|--|--|--|
| /api/register | POST | Register a user | - |
| /api/login | POST | Login a user | - |
| /api/logout| POST | logout a user| yes |
| /api/add-article | POST | Add an article | yes |
| /api/all-categories | GET | Retrieve all categories | - |
| /api/show-all | GET | Retrieve all articles | - | 
| /api/my-articles| GET | Retrieve all my articles (written by the connected user) | yes |  
| /api/show-article/{id} | GET | Retrieve a specific article |- |
| /api/{id}/update-article | PATCH | Edit an article | yes | 
| /api/{id}/delete-article | DELETE | Delete an article | yes |
| /api/edit-profile | PATCH | Edit my profile | yes |
| /api/delete-account | DELETE | Delete my account | yes | 
| /api/{id}/add-comment | POST | Add a comment | yes | 
| /api/{id}/edit-comment | PATCH | Edit my comment | yes | 
| /api/{id}/delete-comment | DELETE | Delete my comment | yes | 
| /api/my-favorites | GET | Retrieve all my favorites articles | yes | 
| /api/{id}/like | POST | Add a like | yes | 
| /api/{id}/dislike| DELETE | Delete a like | yes |


# Author
Myriam K.

# License
This project is open-sourced software licensed under the [MIT](https://opensource.org/license/MIT).

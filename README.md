# Email Client RESTful API

**For trainers:** The API is deployed at https://email-client-api.dev.io-academy.uk/, use this when deploying student-built front-end applications.

<details>
<summary>Run the API locally</summary>

<p></p>
<p>
Clone this repo into your docker `html` folder:

```bash
git clone git@github.com:iO-Academy/email-client-API.git
```

Once cloned, first install the database stored in `db/emails.sql`.
Create a database named `emails`, then open the SQL file in your MySQL GUI and run all queries.

After installing the database, install the vendor code by running the following from the root of the repo:

```bash
composer install
```

To run the application locally:
```bash
composer start
```

**Do not close this terminal tab, it is a running process.**

The API will now be accessible at `http://localhost:8080/`.

That's it! Now go build something cool.
</p>
</details>

## API documentation

### Return all email in the inbox, optionally searched by URL parameter

* **URL**

  /emails

* **Method:**

  `GET`

* **URL Params**

  **Required:**

  There are no required URL params, this URL will return all emails if no params are passed

  **Optional:**

  `search=[alphanumeric]` - a search term which will search `sender_name`, `sender_email`, `subject` and `body`.

  **Example:**

  `/emails?search=code`

* **Success Response:**

    * **Code:** 200 <br />
      **Content:** <br />

  ```json
  {
  "message": "Successfully retrieved emails",
  "data": [
    {
      "id": "539",
      "name": "Nickie Rusted",
      "email": "nrustedey@people.com.cn",
      "subject": "SDA",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-06-30 18:01:08",
      "read": "1"
    },
    {
      "id": "395",
      "name": "Dallas Becaris",
      "email": "dbecarisay@odnoklassniki.ru",
      "subject": "IAR Embedded Workbench",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-06-24 07:22:48",
      "read": "1"
    }
  ]
  }
  ```

* **Error Response:**

    * **Code:** 500 SERVER ERROR <br />
      **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails?search=example search term')
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Return specific email by id, optionally with replies

* **URL**

  /emails/{id}

* **Method:**

  `GET`

* **URL Params**

  **Required:**

  There are no required URL params

  **Optional:**

  `replies=[boolean]` - To include the chosen emails replies or not

  **Example:**

  `/emails/98?replies=1`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** <br />

  ```json
  {
  "message": "Successfully retrieved email",
  "data": {
    "email": {
      "id": "98",
      "name": "Maryjo Ravenhill",
      "email": "mravenhill2p@hugedomains.com",
      "subject": "Biotechnology",
      "date_created": "2022-01-14 04:05:28",
      "body": "Vestibulum quam sapien, varius ut, blandit non, interdum in, ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis faucibus accumsan odio. Curabitur convallis.\n\nDuis consequat dui nec nisi volutpat eleifend. Donec ut dolor. Morbi vel lectus in quam fringilla rhoncus.",
      "read": "1",
      "reply_to": null,
      "deleted": "0",
      "sent": "0"
    },
    "reply": {
      "id": "279",
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Claim",
      "date_created": "2022-07-04 16:05:14",
      "body": "Praesent blandit. Nam nulla. Integer pede justo, lacinia eget, tincidunt eget, tempus vel, pede.\n\nMorbi porttitor lorem id ligula. Suspendisse ornare consequat lectus. In est risus, auctor sed, tristique in, tempus sit amet, sem.\n\nFusce consequat. Nulla nisl. Nunc nisl.",
      "read": "1",
      "reply_to": "98",
      "deleted": "0",
      "sent": "1"
    }
  }
  }
  ```

* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Content:** `{"message": "Invalid email id", "data": []}`

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails/1')
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Send an email

* **URL**

  /emails

* **Method:**

  `POST`

* **URL Params**

  There are no URL params

* **Body Data**

  Must be sent as JSON with the correct headers

  **Required:**

```json
  {
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Example",
      "body": "Exmaple text",
  }
```
  **Optional:**

```json
  {
      "reply": 142
  }
```

* **Success Response:**

    * **Code:** 200 <br />
      **Content:** <br />

  ```json
  {
    "message": "Successfully sent email",
    "data": {
        "sent": true
    }
  }
  ```

* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Content:** `{"message": "Invalid email data", "data": {"sent": false}}`

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails', {
    method: 'POST',
    headers: {
      "content-type": "application/json"
    },
    body: JSON.stringify({
        name: "Bob Ross",
        email: "bob.ross@paintings.com",
        subject: "Example",
        body: "Exmaple text",
      })
  })
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Delete an email

* **URL**

  /emails/{id}

* **Method:**

  `DELETE`

* **URL Params**

  There are no URL params

* **Body Data**

  There is no body data

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** <br />

  ```json
  {
    "message": "Successfully deleted email",
    "data": {
        "deleted": true
    }
  }
  ```

* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Content:** `{"message": "Invalid email id", "data": {"deleted": false}}`

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails/1', {
    method: 'DELETE'
  })
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Mark an email as read

* **URL**

  /emails/{id}

* **Method:**

  `PUT`

* **URL Params**

  There are no URL params

* **Body Data**

  There is no body data

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** <br />

  ```json
  {
    "message": "Successfully updated email",
    "data": {
        "updated": true
    }
  }
  ```

* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Content:** `{"message": "Invalid email id", "data": {"updated": false}}`

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails/1', {
    method: 'PUT'
  })
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Return all sent emails

* **URL**

  /emails/sent

* **Method:**

  `GET`

* **URL Params**

  There are no URL params

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** <br />

  ```json
  {
  "message": "Successfully retrieved emails",
  "data": [
    {
      "id": "103",
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Ultrasonics",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-07-04 16:05:28",
      "read": "1"
    },
    {
      "id": "438",
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Equity Trading",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-07-04 16:05:26",
      "read": "1"
    }
  ]
  }
  ```

* **Error Response:**

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails/sent')
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

### Return all deleted emails

* **URL**

  /emails/deleted

* **Method:**

  `GET`

* **URL Params**

  There are no URL params

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** <br />

  ```json
  {
  "message": "Successfully retrieved emails",
  "data": [
    {
      "id": "103",
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Ultrasonics",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-07-04 16:05:28",
      "read": "1"
    },
    {
      "id": "438",
      "name": "Bob Ross",
      "email": "bob.ross@paintings.com",
      "subject": "Equity Trading",
      "body": "first 50 characters of a description will go here.",
      "date_created": "2022-07-04 16:05:26",
      "read": "1"
    }
  ]
  }
  ```

* **Error Response:**

  * **Code:** 500 SERVER ERROR <br />
    **Content:** `{"message": "Unexpected error", "data": []}`

* **Example Request:**

  ```js
  fetch('https://email-client-api.dev.io-academy.uk/emails/deleted')
    .then(res => res.json())
    .then(data => {
      console.log(data)
    })
  ```

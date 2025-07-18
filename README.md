# Social Todo List API Endpoints

This document provides a comprehensive overview of all available API endpoints for the Social Todo List application.

## Base URL
All API endpoints are prefixed with `/api`

## Authentication
Most endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## Response Format
All responses follow a consistent format:
```json
{
    "success": true/false,
    "message": "Response message",
    "data": {} // Response data (when applicable)
}
```

---

## Authentication Endpoints

### 1. Register User
**POST** `/api/auth/register`

Creates a new user account.

**Request Body:**
```json
{
    "name": "string (required)",
    "username": "string (required, unique)",
    "email": "string (required, unique email format)",
    "password": "string (required, min 6 characters)",
    "type": "string (optional, 'admin' or 'user', defaults to 'user')"
}
```

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "username": "johndoe",
        "email": "john@example.com",
        "type": "user"
    }
}
```

### 2. Login User
**POST** `/api/auth/login`

Authenticates a user and returns user data.

**Request Body:**
```json
{
    "email": "string (required)",
    "password": "string (required)"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Logged in",
    "data": {
        "id": 1,
        "name": "John Doe",
        "username": "johndoe",
        "email": "john@example.com",
        "type": "user"
    }
}
```

### 3. Logout User
**POST** `/api/auth/logout`

Logs out the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

## User Endpoints

### 1. Get Current User
**GET** `/api/user`

Returns the currently authenticated user's information.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "id": 1,
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "type": "user"
}
```

### 2. Search Users
**GET** `/api/search-user?username={search_term}`

Searches for users by username.

**Query Parameters:**
- `username` (string): The username to search for

**Response:**
```json
{
    "success": true,
    "message": "Users found",
    "data": [
        {
            "id": 1,
            "username": "johndoe",
            "name": "John Doe"
        }
    ]
}
```

---

## Todo List Endpoints

All todo list endpoints require authentication and are prefixed with `/api/todos`

### 1. Create Todo List
**POST** `/api/todos/create`

Creates a new todo list. Only admin users can create todo lists.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "title": "string (required)",
    "description": "string (optional)"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Todo list created",
    "data": {
        "id": 1,
        "title": "My Todo List",
        "description": "List description",
        "owner_id": 1,
        "created_at": "2025-07-18T10:00:00.000000Z",
        "updated_at": "2025-07-18T10:00:00.000000Z"
    }
}
```

### 2. Get All Todo Lists
**GET** `/api/todos/list`

Retrieves all todo lists for the authenticated user. Admins see lists they own, regular users see lists they're invited to.

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "success": true,
    "message": "Todo lists retrieved",
    "data": {
        "data": [
            {
                "id": 1,
                "title": "My Todo List",
                "description": "List description",
                "owner": {
                    "id": 1,
                    "name": "John Doe",
                    "username": "johndoe"
                },
                "items": [
                    {
                        "id": 1,
                        "description": "Task description",
                        "completed": false,
                        "creator": {
                            "id": 1,
                            "name": "John Doe"
                        }
                    }
                ],
                "users": [
                    {
                        "id": 2,
                        "name": "Jane Doe",
                        "username": "janedoe"
                    }
                ]
            }
        ],
        "links": {},
        "meta": {}
    }
}
```

### 3. Show Todo List
**GET** `/api/todos/show/{todoList}`

Retrieves a specific todo list with its items and members.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoList` (integer): The ID of the todo list

**Response:**
```json
{
    "success": true,
    "message": "Todo list found",
    "data": {
        "id": 1,
        "title": "My Todo List",
        "description": "List description",
        "owner": {
            "id": 1,
            "name": "John Doe",
            "username": "johndoe"
        },
        "items": [
            {
                "id": 1,
                "description": "Task description",
                "completed": false,
                "creator": {
                    "id": 1,
                    "name": "John Doe"
                }
            }
        ],
        "users": [
            {
                "id": 2,
                "name": "Jane Doe",
                "username": "janedoe"
            }
        ]
    }
}
```

### 4. Update Todo List
**PATCH** `/api/todos/update/{todoList}`

Updates a todo list. Only the owner can update the list.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoList` (integer): The ID of the todo list

**Request Body:**
```json
{
    "title": "string (required)",
    "description": "string (optional)"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Todo list updated",
    "data": {
        "id": 1,
        "title": "Updated Todo List",
        "description": "Updated description",
        "owner_id": 1,
        "created_at": "2025-07-18T10:00:00.000000Z",
        "updated_at": "2025-07-18T11:00:00.000000Z"
    }
}
```

### 5. Delete Todo List
**DELETE** `/api/todos/destroy/{todoList}`

Deletes a todo list. Only the owner can delete the list.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoList` (integer): The ID of the todo list

**Response:**
```json
{
    "success": true,
    "message": "Todo list deleted"
}
```

### 6. Invite User to Todo List
**POST** `/api/todos/invite-user/{todoList}/{user}`

Invites a user to join a todo list. Only the owner can invite users.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoList` (integer): The ID of the todo list
- `user` (integer): The ID of the user to invite

**Response:**
```json
{
    "success": true,
    "message": "User invited to todo list",
    "data": {
        "list": {
            "id": 1,
            "title": "My Todo List"
        },
        "user": {
            "id": 2,
            "name": "Jane Doe",
            "username": "janedoe"
        }
    }
}
```

---

## Todo Item Endpoints

All todo item endpoints require authentication and are prefixed with `/api/todo-items`

### 1. Create Todo Item
**POST** `/api/todo-items/create/{todoList}`

Creates a new todo item in a todo list.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoList` (integer): The ID of the todo list

**Request Body:**
```json
{
    "description": "string (required)"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Item created",
    "data": {
        "id": 1,
        "description": "New task",
        "completed": false,
        "todo_list_id": 1,
        "created_by": 1,
        "creator": {
            "id": 1,
            "name": "John Doe"
        },
        "created_at": "2025-07-18T10:00:00.000000Z",
        "updated_at": "2025-07-18T10:00:00.000000Z"
    }
}
```

### 2. Update Todo Item
**PATCH** `/api/todo-items/update/{todoItem}`

Updates a todo item. Only the creator can update the item.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoItem` (integer): The ID of the todo item

**Request Body:**
```json
{
    "description": "string (required)"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Item updated",
    "data": {
        "id": 1,
        "description": "Updated task",
        "completed": false,
        "todo_list_id": 1,
        "created_by": 1,
        "creator": {
            "id": 1,
            "name": "John Doe"
        },
        "created_at": "2025-07-18T10:00:00.000000Z",
        "updated_at": "2025-07-18T11:00:00.000000Z"
    }
}
```

### 3. Delete Todo Item
**DELETE** `/api/todo-items/destroy/{todoItem}`

Deletes a todo item. Only the creator can delete the item.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoItem` (integer): The ID of the todo item

**Response:**
```json
{
    "success": true,
    "message": "Item deleted"
}
```

### 4. Complete Todo Item
**POST** `/api/todo-items/{todoItem}/complete`

Marks a todo item as completed. Only the creator can complete the item.

**Headers:** `Authorization: Bearer {token}`

**Parameters:**
- `todoItem` (integer): The ID of the todo item

**Response:**
```json
{
    "success": true,
    "message": "Item marked as completed",
    "data": {
        "id": 1,
        "description": "Completed task",
        "completed": true,
        "todo_list_id": 1,
        "created_by": 1,
        "creator": {
            "id": 1,
            "name": "John Doe"
        },
        "created_at": "2025-07-18T10:00:00.000000Z",
        "updated_at": "2025-07-18T12:00:00.000000Z"
    }
}
```

---

## WebSocket Broadcasting

The application supports real-time updates using Laravel Broadcasting. Authentication is required for WebSocket connections.

**Broadcast Route:** `/api/broadcasting/auth`

**Headers:** `Authorization: Bearer {token}`

### Events

- **TodoItemCreated**: Broadcasted when a new todo item is created
- **TodoListInvitation**: Notification sent when a user is invited to a todo list

---

## Error Responses

### Common Error Status Codes

- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Authentication required or invalid credentials
- **403 Forbidden**: Insufficient permissions
- **404 Not Found**: Resource not found
- **409 Conflict**: Resource already exists
- **422 Unprocessable Entity**: Validation errors

### Error Response Format

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

---

## User Types and Permissions

### Admin Users
- Can create todo lists
- Can invite users to their todo lists
- Can update and delete their own todo lists
- Can create, update, delete, and complete todo items in their lists

### Regular Users
- Cannot create todo lists
- Can view todo lists they're invited to
- Can create, update, delete, and complete their own todo items in shared lists
- Cannot invite other users or modify list settings

---

## Pagination

List endpoints that return multiple items support pagination. The response includes:
- `data`: Array of items
- `links`: Navigation links (first, last, prev, next)
- `meta`: Metadata including current page, total items, etc.

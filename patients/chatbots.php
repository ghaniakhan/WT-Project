<?php
// Server-side: Processing chatbot responses if required in PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_key = "AIzaSyDE_uUjMkE9vc6SDroeC_5wkwPmHqydxy8";
    $userMessage = $_POST['message'] ?? '';

    if (!empty($userMessage)) {
        $api_Url = "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key={$api_key}";
        $data = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $userMessage]]
                ]
            ]
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($api_Url, false, $context);

        if ($response === FALSE) {
            echo json_encode(['error' => 'API Request failed']);
        } else {
            $responseData = json_decode($response, true);
            if (
                isset($responseData['candidates'][0]['content']['parts'][0]['text'])
            ) {
                echo json_encode(['response' => $responseData['candidates'][0]['content']['parts'][0]['text']]);
            } else {
                echo json_encode(['error' => 'Invalid API Response']);
            }
        }
    } else {
        echo json_encode(['error' => 'Message cannot be empty']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: white;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .header {
            margin-bottom: 20px;
        }
        #title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .message-area {
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            margin-bottom: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .msg {
            text-align: left;
            margin-bottom: 10px;
        }
        .response {
            text-align: right;
            color: blue;
        }
        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        #toggle-bg {
            margin: 20px 0;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #toggle-bg:hover {
            background-color: #444;
        } */









        body {
    display: flex;
    justify-content: center;
    align-items: center;
    background-size: cover;
    margin: 0;
    font-family: Arial, sans-serif;
    height: 100vh;
    padding: 0;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 95%;
    width: 110%;
    background-color: rgb(33, 74, 88);
    max-width: 500px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
} 

#btn {
    color: rgb(104, 149, 164);
    background-color: rgb(15, 33, 38);
    font-size: bold;
    margin-right: 10px;
    cursor: pointer;
    font-weight: 800;
}
#btn:hover {
    color: rgb(31, 85, 61);
    background-color: rgb(107, 179, 153);
    font-weight: 900;
    font-size: 20px;
} 

.header {
    background-color: rgb(16, 53, 53);
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px 10px 0 0; 
}

#title {
    color: #47a9cd;
    font-weight: 800;
    font-size: 24px;
    text-align: center;
    margin-left: 20px;
}

.message-area {
    flex-grow: 1;
    width: 100%;
    overflow-y: auto;
    margin: 20px 0;
}

#icon {
    color: azure;
    margin: 30px;
    font-size: bold;
    font-weight: 800;
}

.bottom {
    color: rgb(177, 243, 225);
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

#text {
    border-radius: 50px;
    height: 50px;
    width: 100%;
    padding: 0 20px;
    box-sizing: border-box;
    text-align: center;
    background-color: rgb(225, 237, 233);
    font-size: bold;
    font-weight: 400;
}

#text::placeholder {
    color: rgb(188, 172, 172);
    text-align: center;
    font-size: bold;
    font-weight: 400;
}

#toggle-bg {
    color: aliceblue;
    background-color: black;
    margin-right: 10px;
    margin-top: 40%;
}

#sent {
    background: none;
    border: none;
    cursor: pointer;
    margin-left: 10px;
    color: rgb(10, 113, 158);
    font-size: 20px;
}

#sent:hover {
    color: rgb(13, 124, 76);
    font-size: 26px;
}

.msg {
    background-color: rgb(55, 125, 91);
    color: white;
    padding: 10px;
    border-radius: 10px;
    margin: 10px 10px 10px auto;
    max-width: 50%;
    text-align: left;
    word-wrap: break-word;
    align-self: flex-end;
}

.msg.response {
    background-color: rgb(54, 88, 124);
    align-self: flex-start;
}

    </style>
</head>
<body>

    <button id="toggle-bg">Toggle Background</button>

    <div class="container">
        <div class="header">
            <p id="title">CHATBOT</p> 
            <i class="fa-solid fa-image-portrait" id="icon"></i>
        </div>

        <div id="message-area" class="message-area"></div>

        <div class="bottom">
            <input type="text" placeholder="Type something..." id="text">
            <button id="sent">
                <i class="fa-solid fa-paper-plane"></i> Send
            </button>
        </div>
    </div>

    <script>
        let text = document.querySelector("#text");
        let sent_msg = document.querySelector("#sent");
        let toggle_bg = document.querySelector("#toggle-bg");
        let messageArea = document.querySelector("#message-area");
        let currMode = "white";

        // Toggle background color
        toggle_bg.addEventListener("click", () => {
            currMode = currMode === "white" ? "black" : "white";
            document.body.style.backgroundColor = currMode;
        });

        // Display text in the chat
        function DisplayText(msg, isUserMessage = true) {
            if (msg.trim() !== "") {
                const msgElement = document.createElement("div");
                msgElement.classList.add("msg");
                if (!isUserMessage) {
                    msgElement.classList.add("response");
                }
                msgElement.textContent = msg;
                messageArea.appendChild(msgElement);
                messageArea.scrollTop = messageArea.scrollHeight;
                if (isUserMessage) text.value = "";
            }
        }

        // Send message and fetch response
        sent_msg.addEventListener("click", () => {
            const userMessage = text.value;
            if (userMessage.trim()) {
                DisplayText(userMessage, true);

                fetch("chatbots.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `message=${encodeURIComponent(userMessage)}`,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.response) {
                        DisplayText(data.response, false);
                    } else {
                        DisplayText(data.error || "An error occurred.", false);
                    }
                })
                .catch(err => {
                    DisplayText("Error connecting to server.", false);
                    console.error(err);
                });
            } else {
                DisplayText("Please enter a message.", true);
            }
        });
    </script>
</body>
</html>

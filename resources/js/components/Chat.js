import React, {useCallback, useEffect, useState} from 'react';
import ReactDOM from 'react-dom';

function Chat() {
    const [message,setMessage] = useState('');
    const [allUser,setAllUser] = useState([]);
    const [selectedUser,setSelectedUser] = useState(1);
    const [authUser,setAuthUser] = useState('');
    const [allMessages,setAllMessages]= useState([]);
    const [unReadMessage,setUnreadMessage]= useState([]);
    const [showTyping,setShowTyping]= useState(false);
    let allMessagesFromUsers=[];

    //start listening to public channel
    Echo.channel('public-channel')
        .listen('MessageSent',(response)=>{
            console.log(response) ;
        });



    if (authUser != '' && allMessages.length > 0 && unReadMessage.length > 0) {
        const channel = `App.Models.User.${authUser.id}`;
        Echo.private(channel)
            .listen('MessageSent', (response) => {
                let foundMessage = allMessages.find(message=>message.id == response.message.id);
                if(!foundMessage){
                    setUnreadMessage([...unReadMessage,response.message]);
                    setAllMessages([...allMessages,response.message]);
                }
            });
    }

    if(authUser){
        const channel = `App.Models.User.Presence.${authUser.id}`;
        Echo.private(channel)
            .listenForWhisper('typing',(e)=>{
            setShowTyping(true);
            console.log(e.name);
        }).listenForWhisper('typing-end',(e)=>{
            setShowTyping(false);
            console.log(e);
        })
    }

    useEffect( ()=>{
        //getting the auth user and start listening to its channel
        axios.get(`/user`).then(response=>{
            const authenticatedUser = response.data.data;
            setAuthUser(response.data.data);

            //fetching all the users from DB
            axios.get(`/get-all-user`)
                .then( response=>{
                    const tempAllUsers = response.data.data;
                    const tempFilteredUsers = tempAllUsers.filter(user=>user.id != authenticatedUser.id);
                    tempFilteredUsers.forEach(user=>{
                        allMessagesFromUsers.push(...user.messages_from);
                    });
                    let unreadMessages = allMessagesFromUsers.filter((message)=>{
                        if((message.status == 0) && (message.user_to == authenticatedUser.id))
                            return message
                        else
                            return false;
                    })
                    setUnreadMessage(unreadMessages);
                    setAllMessages(allMessagesFromUsers);
                    setAllUser(tempFilteredUsers);
                })
            })
    },[]);

    const updateMessages = id => {
        axios.get(`/all-messages/to_user/${id}`)
            .then(response => {
                const allmess = response.data.data;
                setAllMessages(allmess);
            })
            .catch(error => {
                console.log(error);
            })
    };

    const handleMessageSubmit = (event) => {
        const channel = `App.Models.User.Presence.${selectedUser}`;
        Echo.private(channel).whisper('typing-end',{});
        axios.post(`/send-message`,{message,selectedUser})
            .then(response=>{
                let newMessage = response.data.data;
                setAllMessages([...allMessages,newMessage])
                setMessage('');
            }).catch(error=>console.error(error));
    };

    const handleMessageChange = event => {
        setMessage(event.target.value);
        registerPresenceListener(selectedUser);
    };

    const registerPresenceListener = id =>{
        const channel = `App.Models.User.Presence.${id}`;
        Echo.private(channel).whisper('typing',{name:id});
    }

    const fetchAllMessagesForUser = id => {
        axios.get(`/all-messages/to_user/${id}`)
            .then(response => {
                const allmess = response.data.data
                setAllMessages(allmess);
            })
            .catch(error => {
                console.log(error);
            })
    };

    const markAllUnreadMessageForUser = id => {
       axios.put(`/change-message-status-all/${id}`).then(response=>{
          let tempArr = unReadMessage.filter((message)=>id != message.user_from);
           setUnreadMessage(tempArr);
       })
    };

    const handleSelectedUserChange = id => {
        setSelectedUser(id);
        markAllUnreadMessageForUser(id);
        fetchAllMessagesForUser(id);
    };

    function countUnreadMessages(id) {
        let messages = unReadMessage.filter(message=>{
            return message.user_from == id
        });
        return messages.length;
    }

    const usersList = () => {
        return (
            allUser.map(user=>{
                return <li key={user.id}
                   onClick={()=>{handleSelectedUserChange(user.id)}}
                    className={selectedUser == user.id ? 'selectedUser' : ''}>
                    {user.name}
                    ({countUnreadMessages(user.id)})
                </li>
            })
        )
    }

    const handleClickMessageHandler = id => {
        axios.put(`/update-message-status/${id}`)
            .then(response=>{
            if(response.status==200)
            {
                let tempUnReadMessage = unReadMessage.filter(message=>message.id != id);
                setUnreadMessage(tempUnReadMessage);
            }
        })
    };

    const renderMessages = () =>{
        return (
            allMessages.map(singleMessage=>{
                return <div key={singleMessage.id} onClick={()=>handleClickMessageHandler(singleMessage.id)}>{singleMessage.message}</div>
            })
        )
    }
    useEffect(()=>{
        renderMessages()
    },[selectedUser,allMessages]);
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card">
                        <div className="card-header">Chat</div>
                        <div className="card-body">
                            <div className='col-md-3 d-inline-block'>
                                <p>All Users</p>
                                <ul>
                                    {usersList()}
                                </ul>
                            </div>
                            <div className='col-md-9 d-inline-block' >
                                <p>All Messages</p>
                                {renderMessages()}
                            </div>
                            <div className='col-md-12 text-center'>
                                <input type="text" className="form-control" name="message"
                                       value={message} onChange={()=>{handleMessageChange(event)}}
                                       placeholder={"Please enter your message here."}/>
                                        <p style={{marginTop:'10px',marginBottom:'0px'}}>{showTyping ? `${selectedUser} is typing...`:''}</p>
                                <button className='btn btn-primary mt-2' onClick={()=>{handleMessageSubmit(event)}}>Send Message to {selectedUser}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Chat;

if (document.getElementById('chat')) {
    ReactDOM.render(<Chat/>, document.getElementById('chat'));
}

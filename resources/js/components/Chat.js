import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom';

function Chat() {
    const [message,setMessage] = useState('');
    const [allUser,setAllUser] = useState([]);
    const [selectedUser,setSelectedUser] = useState(1);
    const [authUser,setAuthUser] = useState(1);
    const listenChannel = () => {
        const channel = `${authUser}_${selectedUser}_private_channel`;
        Echo.channel(channel)
        .listen('MessageSent',(response)=>{
            console.log(response) ;
        });
    }

    useEffect(()=>{
        axios.get(`/api/user`)
            .then(response=>{
                setAuthUser(response.data.id);
            })
    },[]);

    useEffect(()=>{
        listenChannel();
        axios.get(`/api/get-all-user`)
            .then(response=>{
                setAllUser(response.data.data);
            })
    },[selectedUser]);

    const handleMessageSubmit = (event) => {
        axios.post(`/api/send-message`,{message,selectedUser})
            .then(response=>{
            }).catch(error=>console.error(error));
    };

    function handleMessageChange(event) {
        setMessage(event.target.value);
    }

    const usersList = () => {
        return (
            allUser.map(user=>{
                return <li key={user.id} onClick={()=>{setSelectedUser(user.id)}} className={selectedUser == user.id ? 'selectedUser' : ''}>{user.name}</li>
            })
        )
    }
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card">
                        <div className="card-header">Chat</div>
                        <div className="card-body">
                            <div className='col-md-4'>
                                <p>All Users</p>
                                <ul>
                                    {usersList()}
                                </ul>
                            </div>
                            <div className='col-md-8'>
                                <input type="text" className="form-control" name="message" onChange={()=>{handleMessageChange(event)}}/>
                                <button className='btn btn-primary' onClick={()=>{handleMessageSubmit(event)}}>Send Message</button>
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
    ReactDOM.render(<Chat />, document.getElementById('chat'));
}

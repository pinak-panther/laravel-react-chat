import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom';
import axios from "axios";

function Profile() {
    const [email, setEmail] = useState('')
    const [name, setName] = useState('')
    const [contact, setContact] = useState('')
    const [profilePic, setProfilePic] = useState('')
    const [uploadedFile, setUploadedFile] = useState('')

    useEffect(() => {
        axios.get(`/profile-data`)
            .then(response => {
                let data = response.data.data;
                setName(data.name);
                setEmail(data.email);
                setContact(data.contact_no);
                setProfilePic(data.profile_pic);
            })
    }, [])

    const nameChangeHandler = event => {
        setName(event.target.value);
    };


    const contactChangeHandler = event => setContact(event.target.value);

    const handleSubmitClick = () => {
        const formData = new FormData();
        if(uploadedFile != ''){
            formData.append('profile_pic',uploadedFile);
        }
        formData.append('name',name);
        formData.append('contact_no',contact);

        axios({
            method: "post",
            url: `/update-profile`,
            data: formData,
            headers: { "Content-Type": "multipart/form-data" },
        }).then(response => {
            console.log(response);
        })
    };

    function handleFileUploadChange(event) {
        setUploadedFile(event.target.files[0])
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-6">
                    <div className="card">
                        <div className="card-header">Profile Page</div>

                        <div className="card-body">
                            <div className={'text-center'}>
                                <img src={profilePic ? `/profile_pics/${profilePic}` : '/profile_pics/default_profile_pic.jpg'}
                                     alt={`${name} Profile Picture`} style={{maxWidth: '250px'}}
                                     className={'mb-3 rounded-circle'}/>
                                <p>Profile Picture</p>
                            </div>
                            <div>
                                <input type="text" value={email} className={'form-control'} placeholder={'Email here'}
                                       disabled={true}/>
                            </div>
                            <div>
                                <input type="text" value={name} onChange={(event) => nameChangeHandler(event)}
                                       className={'form-control mt-3'} placeholder={'Name here'}/>
                            </div>
                            <div>
                                <input type="text" value={contact} onChange={(event) => contactChangeHandler(event)}
                                       className={'form-control mt-3'} placeholder={'Contact here.'}/>
                            </div>
                            <div>
                                <input type="file" className={'form-control mt-3'} onChange={(event) => {handleFileUploadChange(event)}}/>
                            </div>
                            <div>
                                <button className={'form-control mt-3'} onClick={() => {
                                    handleSubmitClick()
                                }}> Update Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Profile;

if (document.getElementById('profile')) {
    ReactDOM.render(<Profile/>, document.getElementById('profile'));
}

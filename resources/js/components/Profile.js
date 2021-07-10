import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom';
import axios from "axios";

function Profile() {
    const [email, setEmail] = useState('')
    const [name, setName] = useState('')
    const [contact, setContact] = useState('')
    const [profilePic, setProfilePic] = useState('')
    const [uploadedFile, setUploadedFile] = useState('')
    const [validationErrors, setValidationErrors] = useState([]);

    const styles = {
        alertContainerStyles:{
            border:'1px solid #fe3b3b',
            borderRadius:'5px',
            marginBottom: '5px',
            marginTop: '5px',
        },
        singleErrorStyles:{
            marginBottom:'5px',
            marginTop:'5px',
            padding:'0px'
        }
    }
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
        setValidationErrors([]);
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
        }).catch(error=>{
            if(error.response.status==422){
                let {errors} = error.response.data;
                // const tempValidationErrors = Object.entries(errors);
                let tempValidationErrors = Object.values(errors);
                let flatValidationErrors = tempValidationErrors.flat(5)
                setValidationErrors(flatValidationErrors);
            }
        })
    };

    const handleFileUploadChange = event => {
        setUploadedFile(event.target.files[0])
    };

    const showValidationErrors = () =>{
        let content,allErrors = null;
        if(validationErrors.length > 0)
        {
            allErrors = validationErrors.map((singleError,index)=>{
              // return <p key={index} style={styles.singleErrorStyles}>{singleError[1]}</p>
              return <p key={index} style={styles.singleErrorStyles}>{singleError}</p>
            })
            content = <div className={'alert-danger text-center'} style={styles.alertContainerStyles}>{allErrors}</div>;
        }
        return content;
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
                            {showValidationErrors()}
                            <div>
                                <input type="text" value={email} className={'form-control'} placeholder={'Email'}
                                       disabled={true}/>

                            </div>
                            <div>
                                <input type="text" value={name} onChange={(event) => nameChangeHandler(event)}
                                       className={'form-control mt-3'} placeholder={'Name'}/>
                            </div>
                            <div>
                                <input type="text" value={contact} onChange={(event) => contactChangeHandler(event)}
                                       className={'form-control mt-3'} placeholder={'Contact Number'}/>
                            </div>
                            <div>
                                <input type="file" className={'form-control mt-3'} onChange={(event) => {handleFileUploadChange(event)}}/>
                            </div>
                            <div>
                                <button className={'form-control mt-3 btn-primary'} onClick={() => {
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

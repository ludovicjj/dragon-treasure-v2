import React, {useState} from 'react';
import LoginForm from "./LoginForm";

export default function TreasureConnect() {
    const [state, setState] = useState({user: null})

    const onUserAuthenticated = function (uri) {
        console.log(uri)
    }

    return <div className="card-wrapper">
        <div className="card">
            <LoginForm userAuthenticated={onUserAuthenticated}/>
        </div>
        <div className="card">
            {state.user ? (
                <div>
                    Authenticated as <strong>{state.user.username}</strong>
                </div>
            ) : (
                <div className="text-center">
                    Not Authenticated
                    <hr className="my-5 mx-auto separator"/>
                    <p>Check out the <a href="/api" className="underline">API Docs</a></p>
                </div>
            )}
        </div>
    </div>
}
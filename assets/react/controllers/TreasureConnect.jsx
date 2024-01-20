import React, {useState} from 'react';
import LoginForm from "./LoginForm";
import {useAppStore} from "../../js/store";

export default function TreasureConnect({user, tokens}) {
    console.log(tokens);
    const [state, setState] = useState({user: user, tokens: tokens})

    const onUserAuthenticated = async (uri) => {
        const response = await fetch(uri);
        const {username, email, apiTokens} = await response.json();

        setState(prevState => ({
            ...prevState,
            user: {username, email},
            tokens: apiTokens.map(item => item.token)
        }));
    }

    return <div className="card-wrapper">
        <div className="card">
            <LoginForm userAuthenticated={onUserAuthenticated}/>
        </div>
        <div className="card">
            {state.user ? (
                <div>
                    Authenticated as <strong>{state.user.username}</strong>
                    | <a href="/logout" className="underline">Log out</a>
                    <h3 className="my-2">Tokens</h3>
                    {state.tokens ? (
                        <div>
                            <ul className="list-group list-unstyled">
                                {state.tokens.map(token => (
                                    <li key={token} className="list-group-item mb-1">{token}</li>
                                ))}
                            </ul>
                        </div>
                    ) : (
                        <div>Refresh to see tokens...</div>
                    )}
                </div>
            ) : (
                <div className="text-center">
                    Not Authenticated
                </div>
            )}
            <hr className="my-5 mx-auto separator"/>
            <p className="text-center">Check out the <a href="/api" className="underline" target="_blank">API Docs</a></p>
        </div>
    </div>
}
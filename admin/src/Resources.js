import React, { useState } from 'react';
import { Button, Paper } from '@material-ui/core';
import axios from 'axios';
import './app.css';
import { getToken } from './Components/Common';

const notifyAgents = async () => {
  try {
    let headers = {
      Accept: 'application/ld+json'
    };
    headers.Authorization = `Bearer ${ getToken() }`;
    const request = ({
      url: `${process.env.REACT_APP_API_ENTRYPOINT}/notify`,
      method: 'POST',
      headers: headers
    });
    await axios.request(request);
  } catch (e) {}
};

export const Notification = () => {
  const [isClicked, setClicked] = useState(false);

  return (
    <Paper>
      { !isClicked ?
        <h1>
          Notifiez tous les agents en cliquant sur <Button variant="contained" color="primary" onClick={() => {
            notifyAgents();
            setClicked(true);
          }}>ce bouton</Button>
        </h1> :
        <h1>Vous avez notifié tous les agents</h1>
      }
    </Paper>
  )
}

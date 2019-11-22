import React, { useState, useEffect } from 'react';
import { Edit } from 'react-admin';
import { getToken, Show } from '../Common';
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import axios from 'axios/index';
import { Button, TextField } from '@material-ui/core';
import SaveIcon from '@material-ui/icons/Save';

const handleUpdateEmail = async (content, name, id) => {
  try {
    let headers = {
      Accept: 'application/ld+json',
      'Content-Type': 'application/json'
    };
    headers.Authorization = `Bearer ${ getToken() }`;
    const request = ({
      url: `${process.env.REACT_APP_API_ENTRYPOINT}/api/emails/${id}`,
      method: 'PUT',
      headers: headers,
      data: JSON.stringify({ content, name })
    });
    await axios.request(request);
  } catch (e) {}
};

const tryEmail = async (id) => {
  try {
    let headers = {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      Authorization: `Bearer ${ getToken() }`
    };
    const request = ({
      url: `${process.env.REACT_APP_API_ENTRYPOINT}/api/emails/${id}/test`,
      method: 'GET',
      headers: headers
    });
    await axios.request(request);
  } catch (e) {}
};

const prodEmail = async (id) => {
  try {
    let headers = {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      Authorization: `Bearer ${ getToken() }`
    };
    const request = ({
      url: `${process.env.REACT_APP_API_ENTRYPOINT}/api/emails/${id}/send`,
      method: 'GET',
      headers: headers
    });
    await axios.request(request);
  } catch (e) {}
};

const CommonForm = ({ content, setContent, name, setName, id, ...rest }) => {
  useEffect(() => {
    setContent(rest.record.content);
    setName(rest.record.name);
  }, [rest.record]);
  return (
    <div style={{ margin: '1rem' }}>
      {
        null !== content ?
          <>
            <TextField
              required
              label="Name"
              defaultValue={ name }
              onChange={ (event) => setName(event.target.value) }
              margin="normal"
            />
            <CKEditor
              editor={ ClassicEditor }
              data={content}
              onChange={ ( event, editor ) => {
                const data = editor.getData();
                setContent(data);
                console.log( { event, editor, data } );
              } }
            />
          </> : 'Chargement de l\'éditeur'
      }
      <h1>Rendu</h1>
      <div dangerouslySetInnerHTML={{__html: content}}/>
      <Button
        onClick={() => handleUpdateEmail(content, name, id)}
        variant="contained"
        color="primary"
        startIcon={<SaveIcon />}
      >
        Save
      </Button>
      <Button
        onClick={() => handleUpdateEmail(content, name, id).then(() => tryEmail(id))}
        variant="contained"
        color="primary"
        startIcon={<SaveIcon />}
      >
        Save & Test
      </Button>
      <Button
        onClick={() => handleUpdateEmail(content, name, id).then(() => prodEmail(id))}
        variant="contained"
        color="secondary"
        startIcon={<SaveIcon />}
      >
        Save & Send to users
      </Button>
    </div>
  )
};

export const EmailShow = props => <Show fields={['content']} {...props}/>;
export const EmailEdit = props => {
  const [content, setContent] = useState(null);
  const [name, setName] = useState(null);
  return (
    <Edit {...props}>
      <CommonForm {...{...props, content, setContent, name, setName, id: props.id.split('/')[3]}}/>
    </Edit>
  )
};

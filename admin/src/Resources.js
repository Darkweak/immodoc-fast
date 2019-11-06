import React, { useState } from 'react';
import {
  HydraAdmin,
  FieldGuesser,
  InputGuesser,
  ResourceGuesser,
  ShowGuesser,
} from '@api-platform/admin';
import { Create, Edit, FileField, FileInput, NumberInput, SimpleForm } from 'react-admin';
import axios from 'axios';
import './app.css';

export const FilesShow = props => (
  <ShowGuesser {...props}>
    <FieldGuesser source="name" addLabel />
    <FieldGuesser source="description" addLabel />
    <FieldGuesser source="price" addLabel />
  </ShowGuesser>
);

const getToken = () => localStorage.getItem('token');

const handleFileAndUpload = async (fileInputElement, inputsElements, url = 'upload', id) => {
  const formData = new FormData();
  const ie = formToJSON(inputsElements);
  formData.append("file", fileInputElement);
  Object.keys(ie).map(o => formData.append(o, ie[o]));
  if (id) {
    formData.append("id", id);
  }
  try {
    let headers = {
      Accept: 'application/ld+json',
      'Content-Type': 'multipart/form-data'
    };
    headers.Authorization = `Bearer ${ getToken() }`;
    const request = ({
      url: `${process.env.REACT_APP_API_ENTRYPOINT}/${url}/files`,
      method: 'POST',
      headers: headers,
      data: formData
    });
    await axios.request(request);
  } catch (e) {}
};

const formToJSON = elements => [].reduce.call(elements, (data, element) => {
  data[element.name] = element.value;
  return data;
}, {});

const FileUploader = ({ setFile }) => {
  return (
    <FileInput source="file" label="Related files" options={{onChange: event => setFile(event.target.files[0])}}>
      <FileField source="src" title="title"/>
    </FileInput>
  )
};

const CommonForm = ({ url = 'upload', id, ...rest }) => {
  const [file, setFile] = useState(null);
  console.log(id)
  return (
    <SimpleForm redirect="list" handleSubmit={async () => await handleFileAndUpload(file, document.forms[0], url, id)} {...rest}>
      <InputGuesser source="name" />
      <InputGuesser source="description" />
      <NumberInput source="price" step={2}/>
      <FileUploader {...{ setFile }}/>
    </SimpleForm>
  )
};

export const FilesEdit = props => (
  <Edit {...props}>
    <CommonForm url="update" {...{...props, id: props.id.split('/')[3]}}/>
  </Edit>
);

export const FilesCreate = props => (
  <Create {...props}>
    <CommonForm/>
  </Create>
);

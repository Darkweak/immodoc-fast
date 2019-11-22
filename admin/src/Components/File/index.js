import React, { useState } from 'react';
import { Create, Edit, FileField, FileInput, NumberInput, SimpleForm } from 'react-admin';
import {
  InputGuesser,
} from '@api-platform/admin';
import { getToken, Show } from '../Common';
import axios from 'axios/index';

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
  return (
    <SimpleForm redirect="list" handleSubmit={async () => await handleFileAndUpload(file, document.forms[0], url, id)} {...rest}>
      <InputGuesser source="name" />
      <InputGuesser source="description" />
      <NumberInput source="price" step={2}/>
      <FileUploader {...{ setFile }}/>
    </SimpleForm>
  )
};

export const FileShow = props => <Show fields={['name', 'description', 'price']} {...props}/>;
export const FileEdit = props => (
  <Edit {...props}>
    <CommonForm url="update" {...{...props, id: props.id.split('/')[3]}}/>
  </Edit>
);
export const FileCreate = props => (
  <Create {...props}>
    <CommonForm/>
  </Create>
);

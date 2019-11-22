import React from 'react';
import {
  HydraAdmin,
  ResourceGuesser,
  dataProvider as baseDataProvider,
  fetchHydra as baseFetchHydra
} from '@api-platform/admin';
import './app.css';
import { FileCreate, FileEdit, FileShow } from './Components/File';
import { Notification } from './Resources';
import { MenuItemLink } from 'react-admin';
import { parseHydraDocumentation } from '@api-platform/api-doc-parser/lib/hydra';
import authProvider from './authProvider';
import { Redirect, Route } from 'react-router-dom';
import { EmailEdit, EmailShow } from './Components/Email';

const entrypoint = `${process.env.REACT_APP_API_ENTRYPOINT}/api`;
const fetchHeaders = {'Authorization': `Bearer ${window.localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
  ...options,
  headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
  .then(
    ({ api }) => ({api}),
    (result) => {
      switch (result.status) {
        case 401:
          return Promise.resolve({
            api: result.api,
            customRoutes: [{
              props: {
                path: '/',
                render: () => <Redirect to={`/login`}/>,
              },
            }],
          });

        default:
          return Promise.reject(result);
      }
    },
  );
const dataProvider = baseDataProvider(entrypoint, fetchHydra, apiDocumentationParser);

export default () => <HydraAdmin {...{
    authProvider,
    apiDocumentationParser,
    customRoutes: [
      <Route exact path="/notification" component={() => <Notification/>} />
    ],
    dataProvider,
    entrypoint,
    menu: ({ resources, onMenuClick }) => (
      <div>
        <MenuItemLink
          to="/files"
          primaryText="Fichiers"
          onClick={onMenuClick} />
        <MenuItemLink
          to="/emails"
          primaryText="Emails"
          onClick={onMenuClick} />
      </div>
    )
  }}>
  <ResourceGuesser name="files" show={FileShow} create={FileCreate} edit={FileEdit}/>
  <ResourceGuesser name="emails" show={EmailShow} edit={EmailEdit}/>
</HydraAdmin>;

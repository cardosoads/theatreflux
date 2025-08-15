import './bootstrap';

import { createApp } from 'vue';
import TestComponent from './components/TestComponent.vue';
import TheatreFlowEditor from './components/TheatreFlowEditor.vue';
import PropertiesPanel from './components/PropertiesPanel.vue';
import AnimationTimeline from './components/AnimationTimeline.vue';
import TestPropertiesPanel from './components/TestPropertiesPanel.vue';

const app = createApp({});

app.component('test-component', TestComponent);
app.component('theatre-flow-editor', TheatreFlowEditor);
app.component('properties-panel', PropertiesPanel);
app.component('animation-timeline', AnimationTimeline);
app.component('test-properties-panel', TestPropertiesPanel);

app.mount('#app');
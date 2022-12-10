import{f as L,T as R,r as v,C as u,ag as U,ah as X,ai as G,U as H,v as M,X as q,a1 as J,a2 as K,o as m,t as x,w as l,b as i,a as s,z as b,x as g,y as P,R as Q,c as W,a7 as Y,a6 as Z,h as ee}from"./vendor-58478656.js";import{l as y}from"./lodash-ab6a0fa0.js";import{d as V}from"./dayjs.min-f8115ce8.js";const ae={class:"p-5"},te=["src"],se={class:"text-center"},le={class:"text-center"},oe=i("div",{class:"i-mdi:upload text-7xl"},null,-1),ne=i("div",{class:"select-none"}," Drop Carousel ",-1),re={class:"flex gap-5 flex-wrap"},ie=["src"],ce={class:"text-center"},ve=L({__name:"carousel",setup(ue){const k=R(),r=v([]),o=v([]),p=v(!1),D=v(!1);function w(e){e.preventDefault()}function T(e){e.preventDefault(),p.value=!0}function j(e){e.preventDefault(),p.value=!1}function B(e){e.preventDefault(),p.value=!1,e.dataTransfer&&h(e.dataTransfer.files)}function $(e){const a=e.target;a.files&&(h(a.files),a.value="")}function h(e){const a=new FormData;for(let t=0;t<e.length;t++)a.append("images[]",e[t],e[t].name);u.post("carousels",a).then(t=>{o.value.push(...t.data),o.value=y.exports.sortBy(o.value,[c=>{var d;return(d=c.filename)==null?void 0:d.toLowerCase()}])})}function A(e){u.put(`/carousels/${e.id}/approved`).then(a=>{Object.assign(e,a.data),r.value.push(e),o.value=o.value.filter(t=>!t.approved)})}function E(e){u.delete(`/carousels/${e.id}/approved`).then(a=>{Object.assign(e,a.data),r.value=r.value.filter(t=>t.id!==e.id),o.value.push(e),o.value=o.value.filter(t=>!t.approved)})}function O(){const e=r.value.map((a,t)=>(a.priority=t+1,a));u.patch("carousels/approved",e).then(()=>{r.value=e,k.success("Carousel order updated.")})}function C(e){u.delete(`/carousels/${e}`).then(()=>{r.value=r.value.filter(a=>a.id!==e),o.value=o.value.filter(a=>a.id!==e)})}u.get("carousels").then(e=>{o.value=e.data,r.value=o.value.filter(a=>a.approved).map(a=>({...a,priority:a.priority??Number.MAX_SAFE_INTEGER})).sort((a,t)=>a.priority-t.priority||V(a.approved_at).diff(V(t.approved_at)))});const z=U(()=>y.exports.sortBy(o.value.filter(e=>!e.approved),[e=>{var a;return(a=e.filename)==null?void 0:a.toLowerCase()}]));return(e,a)=>{const t=X,c=ee,d=G,f=H,N=M("Draggable"),I=q,S=J,F=K;return m(),x(f,{title:"Carousel Setting"},{default:l(()=>[i("div",ae,[s(N,{modelValue:g(r),"onUpdate:modelValue":a[0]||(a[0]=n=>P(r)?r.value=n:null),"item-key":"id",class:"flex gap-5 flex-wrap justify-center",onChange:a[1]||(a[1]=n=>D.value=!0)},{item:l(({element:n})=>[s(f,{class:"cursor-pointer rounded-0 !w-50 !flex items-center gap-5 flex-col",flat:""},{default:l(()=>[i("img",{draggable:"false",class:"object-cover !w-50 aspect-ratio-16/9",src:n.url},null,8,te),s(t,{class:"!py-0"},{default:l(()=>[i("p",se,b(n.filename),1)]),_:2},1024),s(d,{class:"!py-0 flex gap-5 justify-center"},{default:l(()=>[s(c,{icon:"i-mdi:cancel",size:"small",class:"!bg-danger !text-black",onClick:_=>E(n)},null,8,["onClick"]),s(c,{icon:"i-mdi:trash-can",size:"small",class:"!bg-danger !text-black",onClick:_=>C(n.id)},null,8,["onClick"])]),_:2},1024)]),_:2},1024)]),_:1},8,["modelValue"]),i("div",le,[s(c,{color:"primary",onClick:O},{default:l(()=>[Q(" Update Carousel Order ")]),_:1})]),s(f,{class:"mt-10",onDragover:w,onDragenter:T,onDragexit:j,onDrop:B},{default:l(()=>[s(I,{"model-value":g(p),persistent:"",contained:"",class:"items-center justify-center"},{default:l(()=>[oe,ne]),_:1},8,["model-value"]),s(F,{title:"Carousels",color:"rgba(0,0,0,0)"},{default:l(()=>[s(S,null,{default:l(()=>[s(c,{icon:"i-ic:round-add",class:"!bg-success !text-white",tag:"label",for:"carousel"}),i("input",{id:"carousel",type:"file",class:"w-0 h-0 invisible m-0 p-0",multiple:"",onChange:$},null,32)]),_:1})]),_:1}),s(t,null,{default:l(()=>[i("div",re,[(m(!0),W(Z,null,Y(g(z),n=>(m(),x(f,{key:n.id,class:"cursor-pointer rounded-0 !w-60 !flex items-center gap-5 flex-col",flat:""},{default:l(()=>[i("img",{draggable:"false",class:"object-cover !w-60 aspect-ratio-16/9",src:n.url},null,8,ie),s(t,{class:"!py-0"},{default:l(()=>[i("p",ce,b(n.filename),1)]),_:2},1024),s(d,{class:"!py-0 flex gap-5"},{default:l(()=>[s(c,{icon:"i-mdi:check",size:"small",class:"!bg-success !text-black",onClick:_=>A(n)},null,8,["onClick"]),s(c,{icon:"i-mdi:trash-can",size:"small",class:"!bg-danger !text-black",onClick:_=>C(n.id)},null,8,["onClick"])]),_:2},1024)]),_:2},1024))),128))])]),_:1})]),_:1})])]),_:1})}}});export{ve as default};
//# sourceMappingURL=carousel-396ed2ed.js.map

import{f as y,L as _,R as F,r as P,o as g,t as C,w as u,a as t,Q as $,b as c,x as e,P as k,B as f,M as S,h as B,O,S as h,c as j,a6 as T,V as N}from"./vendor.6743af40.js";import{_ as D}from"./ProfilePictureUpdater.vue_vue_type_style_index_0_lang.b239237a.js";import{u as U,_ as I}from"./main.69a876b4.js";import{l as E}from"./lodash.e8efdfbe.js";const M={class:"flex flex-col gap-4"},R=y({__name:"ChangePasswordCard",setup(V){const a=_({oldPassword:"",newPassword:"",repeatPassword:""}),o=_({oldPassword:!1,newPassword:!1,repeatPassword:!1}),p={oldPassword:"",newPassword:"",repeatPassword:""},n=F(),s=_({...p}),d=P(!1);function m(){d.value=!0,Object.assign(s,p),f.post("profile/password",a).then(()=>{n.success("Password updated."),Object.assign(a,p)}).catch(i=>{Object.assign(s,i.response.data.errors)}).finally(()=>{d.value=!1})}return(i,r)=>{const w=S,v=B,x=O,b=h;return g(),C(b,{title:"Change Password",class:"!p-5"},{default:u(()=>[t(x,{onSubmit:$(m,["prevent"])},{default:u(()=>[c("div",M,[t(w,{id:"old-password",modelValue:e(a).oldPassword,"onUpdate:modelValue":r[0]||(r[0]=l=>e(a).oldPassword=l),type:e(o).oldPassword?"text":"password",label:"Old Password","append-icon":e(o).oldPassword?"i-mdi:eye":"i-mdi:eye-off",error:!!e(s).oldPassword,"error-messages":e(s).oldPassword,"onClick:append":r[1]||(r[1]=l=>e(o).oldPassword=!e(o).oldPassword)},null,8,["modelValue","type","append-icon","error","error-messages"]),t(w,{id:"new-password",modelValue:e(a).newPassword,"onUpdate:modelValue":r[2]||(r[2]=l=>e(a).newPassword=l),autocomplete:"new-password",type:e(o).newPassword?"text":"password",label:"New Password","append-icon":e(o).newPassword?"i-mdi:eye":"i-mdi:eye-off",error:!!e(s).newPassword,"error-messages":e(s).newPassword,"onClick:append":r[3]||(r[3]=l=>e(o).newPassword=!e(o).newPassword)},null,8,["modelValue","type","append-icon","error","error-messages"]),t(w,{id:"confirm-password",modelValue:e(a).repeatPassword,"onUpdate:modelValue":r[4]||(r[4]=l=>e(a).repeatPassword=l),autocomplete:"confirm-password",type:e(o).repeatPassword?"text":"password",label:"Repeat Password","append-icon":e(o).repeatPassword?"i-mdi:eye":"i-mdi:eye-off",error:!!e(s).repeatPassword,"error-messages":e(s).repeatPassword,"onClick:append":r[5]||(r[5]=l=>e(o).repeatPassword=!e(o).repeatPassword)},null,8,["modelValue","type","append-icon","error","error-messages"])]),t(v,{class:"!bg-danger text-white dark:text-dark mt-4",type:"submit",loading:e(d)},{default:u(()=>[k(" Change Password ")]),_:1},8,["loading"])]),_:1},8,["onSubmit"])]),_:1})}}}),L={class:"flex-grow-1 flex items-center flex-col"},Q=y({__name:"ProfilePictureCard",setup(V){const a=U(),o=P(null),p=P(null);f.get(`users/${a.id}/profile-picture`,{responseType:"blob"}).then(s=>{o.value=p.value=new File([s.data],"old-file")});function n(s){if(s===null){f.delete(`users/${a.id}/profile-picture`).then(()=>{o.value=null,a.profile_picture="null"});return}const d=new FormData;d.append("image",s,s.name),f.post(`users/${a.id}/profile-picture`,d).then(m=>{a.profile_picture=m.data.profile_picture,o.value=s})}return(s,d)=>{const m=D,i=h;return g(),C(i,{class:"!p-5",title:"Profile Picture"},{default:u(()=>[c("div",L,[t(m,{value:e(o),"onUpdate:value":n},null,8,["value"])])]),_:1})}}}),q={class:"flex flex-col gap-3"},z=y({__name:"FormProfileCard",setup(V){const a={name:"",email:""},o=U(),p=F(),n=_({id:"",name:"",email:""}),s=_({...a}),d=P(!1);f.get("profile").then(i=>{Object.assign(n,E.exports.pick(i.data,["id","name","email"]))});function m(){d.value=!0,Object.assign(s,a),f.patch(`users/${n.id}`,n).then(i=>{o.name=i.data.name,p.success("Profile updated")}).catch(i=>{Object.assign(s,i.response.data.errors)}).finally(()=>{d.value=!1})}return(i,r)=>{const w=S,v=B,x=O,b=h;return g(),C(b,{class:"!p-5",title:"Profile"},{default:u(()=>[t(x,{class:"flex-grow-2 lg:flex-grow-1",onSubmit:$(m,["prevent"])},{default:u(()=>[c("div",q,[t(w,{modelValue:e(n).name,"onUpdate:modelValue":r[0]||(r[0]=l=>e(n).name=l),label:"Name",error:!!e(s).name,"error-messages":e(s).name},null,8,["modelValue","error","error-messages"]),t(w,{modelValue:e(n).email,"onUpdate:modelValue":r[1]||(r[1]=l=>e(n).email=l),label:"Email",error:!!e(s).email,"error-messages":e(s).email},null,8,["modelValue","error","error-messages"])]),t(v,{class:"mt-4",type:"submit",color:"primary",loading:e(d)},{default:u(()=>[k(" Submit ")]),_:1},8,["loading"])]),_:1},8,["onSubmit"])]),_:1})}}}),A={},G=c("h1",{class:"text-3xl text-themeable"}," Profile Settings ",-1),H={class:"grid grid-cols-1 lg:grid-cols-2 gap-4"},J={class:"lg:row-span-2"};function K(V,a){const o=N,p=z,n=Q,s=R;return g(),j(T,null,[G,t(o,{class:"my-4"}),c("div",H,[c("div",null,[t(p)]),c("div",J,[t(n)]),c("div",null,[t(s)])])],64)}const ee=I(A,[["render",K]]);export{ee as default};
//# sourceMappingURL=profile.1014e05e.js.map

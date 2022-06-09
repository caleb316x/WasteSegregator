<template>
    <div>
        <!-- <img width="200" src="@/assets/images/bottle1.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/cat.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/paper.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/test.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/trash.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/bottles.jpg" id="target" alt=""> -->
        <!-- <img width="400" src="@/assets/images/sachet.jpg" id="target" alt=""> -->
        <!-- <video id = "cam"></video> -->
        <div id="webcam-container"></div>
        <div id="label-container"></div>
        <div v-if="detection!=null">
            <div v-for="(item,index) in detection" v-bind:key="index">
                <div id="detections">Object: {{item["className"]}} | Score: {{(parseFloat(item["probability"])*100).toFixed(2) }}%</div>
            </div>
        </div>
        <hr>
        <!-- <button v-on:click="loadModel()">Start</button> -->
        <!-- <button v-on:click="getDetection()">Detect</button> -->
        <button v-on:click="getCam()">Capture</button>
        <button v-on:click="check_drop()">Check</button>
        <button v-on:click="send_detect()">predict</button>
    </div>
</template>

<script>
    import * as tf from '@tensorflow/tfjs';
    import * as tmImage from '@teachablemachine/image';
    // import * as cocoSsd from '@tensorflow-models/coco-ssd';
    // import * as mobileNet from '@tensorflow-models/mobilenet';
    let webcam; 

    export default {
        name: 'TensorFlow',
        data() {
            return {
                detection: null,
                model: null,
                url: "https://storage.googleapis.com/tm-model/baz4YquQt"
            }
        },
        created() {
            this.loadModel()
        },
        methods:{
            async loadModel(){
                console.log("Loading model")
                this.model = await tmImage.load(this.url+'/model.json', this.url+'/metadata.json');
                console.log("Done!")
                console.log("init cam");
                webcam = new tmImage.Webcam(200, 200, true); 
                await webcam.setup(); // request access to the webcam
                await webcam.play();
                console.log("Done!!")
                this.check_drop();
            },
            async getDetection() {
                const model = await tmImage.load(this.url+'/model.json', this.url+'/metadata.json');
                this.detection = await model.predict(document.getElementById('target'));
                this.detection.forEach(d => {
                    console.log(d["className"])
                    console.log("Score: "+(parseFloat(d["probability"]))*100)
                });
            },
            async getCam(){
                console.log("open cam");
                const model = await tmImage.load(this.url+'/model.json', this.url+'/metadata.json');
                let maxPredictions = model.getTotalClasses();
                webcam.update();
                document.getElementById("webcam-container").innerHTML = '';
                document.getElementById("webcam-container").appendChild(webcam.canvas);
                let labelContainer = document.getElementById("label-container");
                for (let i = 0; i < maxPredictions; i++) { // and class labels
                    labelContainer.appendChild(document.createElement("div"));
                }

                this.detection = await model.predictTopK(webcam.canvas);
                this.detection.forEach(d => {
                    console.log(d["className"])
                    console.log("Score: "+(parseFloat(d["probability"]))*100)
                });

                if(this.detection.length > 0){
                   this.send_detect(this.detection[0]["className"]);
                }
            },
            async check_drop(){
                console.log("Checking");
                var t = this;
                let res = null;
                
                await fetch("/check",
                    {
                        method: "GET", 
                        mode: 'cors',
                        headers: {
                            'Content-Type': '*/*',
                            'Access-Control-Allow-Origin':'*'
                        }
                    }
                )
                .then(response=>response.text())
                .then(
                    data => data == "capture" ? t.getCam() : t.check_drop()
                );
            },
            async send_detect(garbageClass = "caleb"){
                console.log("Update detectfile",garbageClass);
                var t = this;
                
                await fetch("/prediction/"+garbageClass,
                    {
                        method: "GET", 
                        mode: 'cors',
                        headers: {
                            'Content-Type': '*/*',
                            'Access-Control-Allow-Origin':'*'
                        },
                    }
                )
                .then(response=>response.text())
                .then(
                    data => console.log("predict",data)
                );

                this.check_drop();
            }

        }
    }
</script>
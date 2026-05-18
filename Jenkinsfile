// pipeline {
//     agent any

//         environment {
//         DOCKERHUB_USER = 'zeyadgamal'
//         IMAGE_TAG = "${DOCKERHUB_USER}/service-app:${BUILD_NUMBER}"
//     }

//     stages {

//         stage('Checkout') {
//             steps {
//                 checkout scmGit(
//                     branches: [[name: 'main']],
//                     userRemoteConfigs: [[
//                         url: 'https://github.com/Zeyad-Gamal/Jenkins-Final-Assignment.git',
//                         credentialsId: 'github-creds'
//                     ]]
//                 )
//             }
//         }

//         stage('SonarQube Analysis') {
//     steps {
//         withSonarQubeEnv('SonarQube-Server') {
//             sh """
//                 ${tool 'SonarScanner'}/bin/sonar-scanner \
//                 -Dsonar.projectKey=service-app \
//                 -Dsonar.sources=./src \
//                 -Dsonar.host.url=http://sonarqube:9000
//             """
//         }
//     }
// }



//         stage('Build Docker Image') {
//             steps {
//                 sh "docker build -t ${IMAGE_TAG} ."
//             }
//         }

//                 stage('Push to Docker Hub') {
//     steps {
//         withCredentials([usernamePassword(
//             credentialsId: 'dockerhub-creds',
//             usernameVariable: 'DOCKER_USER',
//             passwordVariable: 'DOCKER_PASS'
//         )]) {

//             sh '''
//                 echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin

//                 docker push ${IMAGE_TAG}
//             '''
//         }
//     }
// }

//         stage('Run Unit Tests') {
//             steps {
//                 catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
//                     sh '/usr/local/bin/phpunit --log-junit results.xml tests/'
//                 }
//             }
//         }

//         stage('Display Results') {
//             steps {
//                 junit 'results.xml'
//             }
//         }

//     }

//     post {
//         always {
//             echo 'Pipeline job finished.'
//         }
//         success {
//             echo 'Congratulations! All tests passed successfully.'
//         }
//         failure {
//             echo 'The code failed the tests! Please check the Test Result trend for details.'
//         }
//     }
// }





























pipeline {
    agent any

    environment {
        DOCKERHUB_USER = 'zeyadgamal'
        IMAGE_TAG = "${DOCKERHUB_USER}/service-app:${BUILD_NUMBER}"
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scmGit(
                    branches: [[name: 'main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/Zeyad-Gamal/Jenkins-Final-Assignment.git',
                        // credentialsId: 'github-creds'
                        credentialsId: 'jenkins-lab'
                    ]]
                )
            }
        }

        // stage('SonarQube Analysis') {
        //     steps {
        //         withSonarQubeEnv('SonarQube-Server') {
        //             sh """
        //                 ${tool 'SonarScanner'}/bin/sonar-scanner \
        //                 -Dsonar.projectKey=service-app \
        //                 -Dsonar.sources=./src \
        //                 -Dsonar.host.url=http://localhost:9000
        //             """
        //         }
        //     }
        // }


        stage('SonarQube Analysis') {
    steps {
        withSonarQubeEnv('SonarQube-Server') {
            withCredentials([string(credentialsId: 'service-app', variable: 'SONAR_TOKEN')]) {
                sh """
                    ${tool 'SonarScanner'}/bin/sonar-scanner \
                    -Dsonar.projectKey=service-app \
                    -Dsonar.sources=./src \
                    -Dsonar.host.url=http://localhost:9000 \
                    -Dsonar.login=$SONAR_TOKEN
                """
            }
        }
    }
}



stage('Quality Gate') {
    steps {
        timeout(time: 2, unit: 'MINUTES') {
            waitForQualityGate abortPipeline: true
        }
    }
}

        stage('Build Docker Image') {
            steps {
                sh "docker build -t ${IMAGE_TAG} ."
            }
        }


//         stage('Trivy Image Scan') {
//     steps {
//         sh """
//             trivy image \
//               --exit-code 1 \
//               --severity CRITICAL \
//               --no-progress \
//               zeyadgamal/service-app:${BUILD_NUMBER}
//         """
//     }
// }

//         stage('Trivy Image Scan') {
//     steps {
//         sh """
//             trivy image \
//               --exit-code 1 \
//               --severity CRITICAL \
//               --no-progress \
//               ${IMAGE_TAG}
//         """
//     }
// }


//                 stage('Trivy Image Scan') {
//     steps {
//         sh """
//             trivy image \
//               --exit-code 1 \
//               --severity CRITICAL \
//               --no-progress \
//               ${IMAGE_TAG}
//         """
//     }
// }

        stage('Trivy Image Scan') {
    steps {
        sh 'trivy fs --severity CRITICAL --exit-code 0 .'
        
    }
}

        stage('Push to Docker Hub') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub-creds',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {

                    sh """
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push ${IMAGE_TAG}
                    """
                }
            }
        }

        stage('Deploy') {
    steps {
        sh """
            docker stop app || true
            docker rm app || true
            docker run -d \
              --name app \
              -p 8081:8080 \
              ${IMAGE_TAG}
        """
    }
}

        stage('Run Unit Tests') {
            steps {
                catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                    sh '/usr/local/bin/phpunit --log-junit results.xml tests/'
                }
            }
        }

        stage('Display Results') {
            steps {
                junit 'results.xml'
            }
        }
    }

    post {
        always {
        echo 'Pipeline finished.'

        sh """
            docker rmi ${IMAGE_TAG} || true
            docker image prune -f
        """
    }

    success {
        echo 'All stages completed successfully.'
    }

    failure {
        echo 'Pipeline failed. Check logs.'
    }
    }
}

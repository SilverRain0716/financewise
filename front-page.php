<?php 
/**
 * Front Page Template
 * * WordPress 템플릿 계층에 의해 자동으로 프론트 페이지로 사용됩니다.
 * (Settings > Reading > "A static page" 설정 시)
 * * 참고: Template Name 헤더를 제거했습니다.
 * front-page.php는 WordPress 템플릿 계층 파일이므로
 * Template Name을 지정하면 페이지 템플릿 드롭다운에 중복 표시됩니다.
 */
get_header(); ?>

<!-- 영웅 섹션 (Hero Section) -->
<section class="fw-hero" style="position: relative;">
    <!-- 1. 기존 그라데이션 배경 (Three.js 캔버스 뒤에 깔림) -->
    <div class="fw-hero-overlay"></div>

    <!-- 2. [추가됨] Three.js 3D 애니메이션이 렌더링될 배경 캔버스 컨테이너 -->
    <!-- pointer-events: none을 주어 앞쪽의 검색창이나 링크 클릭을 방해하지 않게 합니다. -->
    <div id="fw-3d-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; pointer-events: none;"></div>

    <!-- 3. 기존 텍스트 및 검색 폼 (z-index: 1 로 3D 캔버스 위에 위치) -->
    <div class="fw-hero-inner" style="position: relative; z-index: 1;">
        <div class="fw-hero-content-box">
            <span class="fw-hero-badge" style="background:var(--primary); color:var(--navy); padding:5px 10px; border-radius:4px; font-weight:bold; font-size:0.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">PREMIUM INSIGHTS 2026</span>
            <!-- 텍스트 가독성을 높이기 위해 text-shadow 추가 -->
            <h1 class="fw-hero-title" style="text-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                The Future of <br>
                FinanceWise <span class="fw-italic-accent">Magazine</span>
            </h1>
            <p class="fw-hero-sub" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);">
                Strategic financial guidance for sustainable wealth growth. <br>
                Expert analysis for the modern investor.
            </p>
            
            <!-- 검색 폼 -->
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="fw-search-form" style="max-width:500px; margin:0 auto; position:relative; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border-radius: 25px;">
                <input type="search" class="fw-input" placeholder="Search insights, trends..." name="s" style="padding-right: 100px; height:50px; border-radius:25px; background: rgba(255,255,255,0.15); backdrop-filter: blur(5px);" />
                <button type="submit" class="fw-search-btn" style="position:absolute; right:5px; top:5px; height:40px; border-radius:20px; border:none; background:var(--primary); color:var(--navy); font-weight:bold; padding:0 20px; cursor:pointer;">Search</button>
            </form>
        </div>
    </div>
</section>

<!-- [추가됨] Three.js 코어 및 로더, 그리고 실행 스크립트 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. 기본 설정 (Scene, Camera, Renderer)
    const container = document.getElementById('fw-3d-canvas');
    const heroSection = document.querySelector('.fw-hero');
    const scene = new THREE.Scene();
    
    // 테마의 var(--navy) 색상(#0a192f)과 맞춰 안개 효과 적용하여 자연스럽게 배경과 섞이게 함
    scene.fog = new THREE.FogExp2(0x0a192f, 0.04); 

    const camera = new THREE.PerspectiveCamera(60, heroSection.clientWidth / heroSection.clientHeight, 0.1, 1000);
    // 텍스트 뒤 배경으로 보이도록 카메라를 약간 뒤로 배치
    camera.position.set(0, 2, 9); 

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true }); // 배경 투명 처리
    renderer.setSize(heroSection.clientWidth, heroSection.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true; 
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    // 2. 조명 설정
    const ambientLight = new THREE.AmbientLight(0x0f203a, 1.8); 
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffdfb0, 1.5); 
    dirLight.position.set(5, 6, 4);
    dirLight.castShadow = true;
    dirLight.shadow.mapSize.width = 1024;
    dirLight.shadow.mapSize.height = 1024;
    scene.add(dirLight);

    const emeraldLight = new THREE.PointLight(0x50c878, 2.5, 20); 
    emeraldLight.position.set(-5, 2, -3);
    scene.add(emeraldLight);

    const rimLight = new THREE.PointLight(0x0055ff, 3, 20); 
    rimLight.position.set(0, 3, -5);
    scene.add(rimLight);

    // 3. 바닥 그리드 
    const gridHelper = new THREE.GridHelper(50, 50, 0x64ffda, 0x112240); // 테마 primary color 적용
    gridHelper.position.y = -2;
    gridHelper.material.opacity = 0.15;
    gridHelper.material.transparent = true;
    scene.add(gridHelper);

    // 4. 리얼+클레이 스타일 황소 (소의 실제 특징과 디테일 업그레이드)
    const bullGroup = new THREE.Group();
    
    const bodyMat = new THREE.MeshStandardMaterial({ color: 0xa8652d, roughness: 0.4, metalness: 0.1 }); // 더 따뜻한 브라운
    const muzzleMat = new THREE.MeshStandardMaterial({ color: 0xebb788, roughness: 0.5 }); // 밝은 소 주둥이
    const hornMat = new THREE.MeshStandardMaterial({ color: 0xfffae6, roughness: 0.2, metalness: 0.1 });
    const noseMat = new THREE.MeshStandardMaterial({ color: 0x4a2c14, roughness: 0.6 });
    const eyeMat = new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 0.1 });
    const hoofMat = new THREE.MeshStandardMaterial({ color: 0x222222, roughness: 0.7 }); // 까만 발굽 색상 추가

    // 몸통 (더 통통하고 둥글게)
    const bodyGeo = new THREE.SphereGeometry(1, 64, 64);
    const body = new THREE.Mesh(bodyGeo, bodyMat);
    body.scale.set(1.05, 0.9, 1.4);
    body.position.set(0, 0.5, 0);
    body.castShadow = true;
    bullGroup.add(body);

    // 머리
    const headGeo = new THREE.SphereGeometry(0.65, 64, 64);
    const head = new THREE.Mesh(headGeo, bodyMat);
    head.scale.set(1, 0.95, 1.1);
    head.position.set(0, 1.0, 1.1);
    head.castShadow = true;
    bullGroup.add(head);

    // 주둥이 (소 특유의 넓적하고 둥근 형태 강조)
    const snoutGeo = new THREE.SphereGeometry(0.48, 64, 64);
    const snout = new THREE.Mesh(snoutGeo, muzzleMat);
    snout.scale.set(1.3, 0.85, 1);
    snout.position.set(0, 0.8, 1.55);
    snout.castShadow = true;
    bullGroup.add(snout);

    // 코 (더 양옆으로 벌어지게 배치)
    const nostrilGeo = new THREE.SphereGeometry(0.08, 32, 32);
    const nostrilL = new THREE.Mesh(nostrilGeo, noseMat);
    nostrilL.scale.set(1.2, 0.8, 1);
    nostrilL.position.set(0.3, 0.9, 1.95);
    const nostrilR = new THREE.Mesh(nostrilGeo, noseMat);
    nostrilR.scale.set(1.2, 0.8, 1);
    nostrilR.position.set(-0.3, 0.9, 1.95);
    bullGroup.add(nostrilL, nostrilR);

    // 뿔 (조금 더 두껍고 소처럼 곡선감 부여)
    const hornGeo = new THREE.ConeGeometry(0.15, 0.7, 32);
    hornGeo.translate(0, 0.35, 0); 
    const hornL = new THREE.Mesh(hornGeo, hornMat);
    hornL.position.set(0.55, 1.25, 1.0);
    hornL.rotation.z = -Math.PI / 3;
    hornL.rotation.x = Math.PI / 8;
    const hornR = new THREE.Mesh(hornGeo, hornMat);
    hornR.position.set(-0.55, 1.25, 1.0);
    hornR.rotation.z = Math.PI / 3;
    hornR.rotation.x = Math.PI / 8;
    bullGroup.add(hornL, hornR);

    // 귀 (소 특유의 양옆으로 처진 넓은 귀)
    const earGeo = new THREE.SphereGeometry(0.25, 32, 32);
    const earL = new THREE.Mesh(earGeo, bodyMat);
    earL.scale.set(1.2, 0.4, 0.2);
    earL.position.set(0.8, 1.0, 0.8);
    earL.rotation.z = -Math.PI / 8;
    const earR = new THREE.Mesh(earGeo, bodyMat);
    earR.scale.set(1.2, 0.4, 0.2);
    earR.position.set(-0.8, 1.0, 0.8);
    earR.rotation.z = Math.PI / 8;
    bullGroup.add(earL, earR);

    // 눈 & 하이라이트 (비율 조정)
    const eyeGeo = new THREE.SphereGeometry(0.12, 32, 32);
    const eyeL = new THREE.Mesh(eyeGeo, eyeMat);
    eyeL.position.set(0.48, 1.15, 1.4);
    const eyeR = new THREE.Mesh(eyeGeo, eyeMat);
    eyeR.position.set(-0.48, 1.15, 1.4);
    const pupilGeo = new THREE.SphereGeometry(0.04, 16, 16);
    const pupilMat = new THREE.MeshBasicMaterial({color: 0xffffff});
    const pupilL = new THREE.Mesh(pupilGeo, pupilMat);
    pupilL.position.set(0.05, 0.05, 0.09);
    eyeL.add(pupilL);
    const pupilR = new THREE.Mesh(pupilGeo, pupilMat);
    pupilR.position.set(-0.05, 0.05, 0.09);
    eyeR.add(pupilR);
    bullGroup.add(eyeL, eyeR);

    // 다리 & 발굽 (Hooves - 소의 까만 발굽 디테일 추가)
    const legGeo = new THREE.CylinderGeometry(0.2, 0.15, 0.7, 32);
    legGeo.translate(0, -0.35, 0); 
    const hoofGeo = new THREE.CylinderGeometry(0.16, 0.18, 0.2, 32);
    hoofGeo.translate(0, -0.8, 0);

    const createLeg = (x, y, z) => {
        const legGroup = new THREE.Group();
        const legMesh = new THREE.Mesh(legGeo, bodyMat);
        legMesh.castShadow = true;
        const hoofMesh = new THREE.Mesh(hoofGeo, hoofMat);
        hoofMesh.castShadow = true;
        legGroup.add(legMesh, hoofMesh);
        legGroup.position.set(x, y, z);
        return legGroup;
    };

    const legFR = createLeg(-0.5, 0.2, 0.8);
    const legFL = createLeg(0.5, 0.2, 0.8);
    const legBR = createLeg(-0.5, 0.2, -0.8);
    const legBL = createLeg(0.5, 0.2, -0.8);
    bullGroup.add(legFR, legFL, legBR, legBL);

    // 꼬리 & 꼬리털 (Tuft - 꼬리 끝 까만 털 디테일 추가)
    const tailGeo = new THREE.CylinderGeometry(0.04, 0.02, 0.7, 16);
    tailGeo.translate(0, -0.35, 0);
    const tail = new THREE.Mesh(tailGeo, bodyMat);
    tail.position.set(0, 0.7, -1.3);
    tail.rotation.x = -Math.PI / 5;
    
    const tuftGeo = new THREE.SphereGeometry(0.08, 16, 16);
    const tuft = new THREE.Mesh(tuftGeo, hoofMat);
    tuft.scale.set(1, 1.5, 1);
    tuft.position.set(0, -0.7, 0);
    tail.add(tuft);
    bullGroup.add(tail);
    
    // 황소 전체 크기를 70%로 축소
    bullGroup.scale.set(0.7, 0.7, 0.7);
    
    // 텍스트 아래쪽 백그라운드에 위치하도록 y축 위치 약간 하향 조정
    bullGroup.position.set(0, -1.2, -1);
    // 방향을 오른쪽으로 변경
    bullGroup.rotation.y = Math.PI / 6;
    scene.add(bullGroup);


    // 5. 금융 흐름 파티클 시스템
    const particleCount = 2000;
    const particleGeo = new THREE.BufferGeometry();
    const particlePositions = new Float32Array(particleCount * 3);
    const originalPositions = new Float32Array(particleCount * 3);
    const particleColors = new Float32Array(particleCount * 3);

    const colorDeepBlue = new THREE.Color(0x0055ff);
    const colorEmerald = new THREE.Color(0x64ffda); // 테마 primary color 연동

    for(let i = 0; i < particleCount; i++) {
        let x = (Math.random() - 0.5) * 40;
        let y = (Math.random() - 0.5) * 10 - 2;
        let z = (Math.random() - 0.5) * 20 - 5;

        particlePositions[i*3] = x;
        particlePositions[i*3+1] = y;
        particlePositions[i*3+2] = z;
        originalPositions[i*3] = x;
        originalPositions[i*3+1] = y;
        originalPositions[i*3+2] = z;

        let mixColor = colorDeepBlue.clone().lerp(colorEmerald, Math.random());
        particleColors[i*3] = mixColor.r;
        particleColors[i*3+1] = mixColor.g;
        particleColors[i*3+2] = mixColor.b;
    }

    particleGeo.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
    particleGeo.setAttribute('color', new THREE.BufferAttribute(particleColors, 3));
    particleGeo.setAttribute('aOriginal', new THREE.BufferAttribute(originalPositions, 3));

    const particleMat = new THREE.PointsMaterial({
        size: 0.06,
        vertexColors: true,
        transparent: true,
        opacity: 0.6,
        blending: THREE.AdditiveBlending
    });

    const particles = new THREE.Points(particleGeo, particleMat);
    scene.add(particles);

    // 6. 돈($ 기호) 파티클 시스템
    function createSymbolTexture(symbol, color) {
        const canvas = document.createElement('canvas');
        canvas.width = 128;
        canvas.height = 128;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.font = 'bold 80px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.shadowColor = color;
        ctx.shadowBlur = 15;
        ctx.fillText(symbol, 64, 64);
        return new THREE.CanvasTexture(canvas);
    }

    const dollarTex = createSymbolTexture('$', '#64ffda'); // 테마 색상으로 변경
    const euroTex = createSymbolTexture('€', '#64ffda'); 
    
    const symbols = [];
    const symbolMaterials = [
        new THREE.SpriteMaterial({ map: dollarTex, transparent: true, blending: THREE.AdditiveBlending }),
        new THREE.SpriteMaterial({ map: euroTex, transparent: true, blending: THREE.AdditiveBlending })
    ];

    for(let i=0; i<20; i++) {
        let sprite = new THREE.Sprite(symbolMaterials[i % 2]);
        sprite.scale.set(0.3, 0.3, 0.3);
        sprite.userData = {
            x: (Math.random() - 0.5) * 2 - 1,
            y: Math.random() * 2 - 2,
            z: (Math.random() - 0.5) * 2,
            speedY: Math.random() * 0.015 + 0.01,
            speedX: Math.random() * 0.01 - 0.02,
            life: Math.random()
        };
        sprite.position.set(sprite.userData.x, sprite.userData.y, sprite.userData.z);
        scene.add(sprite);
        symbols.push(sprite);
    }

    // 7. 마우스 인터랙션 설정 (컨테이너가 아닌 hero 섹션 전체에서 이벤트 수신)
    const mouse = new THREE.Vector2(-100, -100);
    const targetMouse3D = new THREE.Vector3(0, 0, 0);

    heroSection.addEventListener('mousemove', (event) => {
        const rect = heroSection.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
    });
    
    heroSection.addEventListener('mouseleave', () => {
        mouse.x = -100; 
        mouse.y = -100;
    });

    // 8. 애니메이션 루프
    const clock = new THREE.Clock();
    function animate() {
        requestAnimationFrame(animate);
        const time = clock.getElapsedTime();

        // 황소 애니메이션
        bullGroup.position.y = -1.2 + Math.sin(time * 5) * 0.08; 
        legFL.rotation.x = Math.sin(time * 10) * 0.4;
        legBR.rotation.x = Math.sin(time * 10) * 0.4;
        legFR.rotation.x = Math.sin(time * 10 + Math.PI) * 0.4;
        legBL.rotation.x = Math.sin(time * 10 + Math.PI) * 0.4;
        tail.rotation.z = Math.sin(time * 15) * 0.2;

        // 마우스 3D 위치 추적
        const vector = new THREE.Vector3(mouse.x, mouse.y, 0.5);
        vector.unproject(camera);
        const dir = vector.sub(camera.position).normalize();
        const distance = -camera.position.z / dir.z; 
        targetMouse3D.copy(camera.position).add(dir.multiplyScalar(distance));

        // 배경 파티클
        const positions = particleGeo.attributes.position.array;
        const originals = particleGeo.attributes.aOriginal.array;

        for(let i = 0; i < particleCount; i++) {
            let ix = i * 3, iy = i * 3 + 1, iz = i * 3 + 2;
            let ox = originals[ix], oy = originals[iy], oz = originals[iz];

            let targetX = ox + Math.cos(time * 0.5 + oz) * 0.5;
            let targetY = oy + Math.sin(time * 1.2 + ox) * 0.3;
            let targetZ = oz;

            let dx = positions[ix] - targetMouse3D.x;
            let dy = positions[iy] - targetMouse3D.y;
            let dz = positions[iz] - targetMouse3D.z;
            let distToMouse = Math.sqrt(dx*dx + dy*dy + dz*dz);

            if(mouse.x !== -100 && distToMouse < 4.0) {
                let force = (4.0 - distToMouse) / 4.0;
                targetX -= dx * force * 0.8;
                targetY -= dy * force * 0.8;
                targetZ -= dz * force * 0.8;
            }

            positions[ix] += (targetX - positions[ix]) * 0.1;
            positions[iy] += (targetY - positions[iy]) * 0.1;
            positions[iz] += (targetZ - positions[iz]) * 0.1;
        }
        particleGeo.attributes.position.needsUpdate = true;

        // 금융 기호 파티클
        symbols.forEach(sprite => {
            let data = sprite.userData;
            data.life += 0.01;
            sprite.position.y += data.speedY;
            sprite.position.x += data.speedX;
            
            let lifeCycle = data.life % 1.0;
            sprite.material.opacity = 1.0 - lifeCycle;
            let s = 0.3 + lifeCycle * 0.5;
            sprite.scale.set(s, s, s);

            if(data.life > 1.0) {
                data.life = 0;
                sprite.position.set(
                    (Math.random() - 0.5) * 3 - 1, 
                    Math.random() * 1 - 2,         
                    (Math.random() - 0.5) * 3      
                );
            }
        });

        // 카메라 반응
        camera.position.x += (mouse.x * 0.5 - camera.position.x) * 0.05;
        camera.position.y += (mouse.y * 0.5 + 2 - camera.position.y) * 0.05;
        camera.lookAt(0, 0, 0);

        renderer.render(scene, camera);
    }
    animate();

    // 9. 브라우저 크기 변경 대응
    window.addEventListener('resize', () => {
        const width = heroSection.clientWidth;
        const height = heroSection.clientHeight;
        renderer.setSize(width, height);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    });
});
</script>

<!-- 메인 컨텐츠 영역 -->
<div class="fw-main-container">
    <main id="main">
        <?php
        // 'Uncategorized'를 제외한 모든 카테고리 가져오기
        $categories = get_categories( array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
            'exclude'    => array( get_option('default_category') ),
        ) );

        if ( ! empty( $categories ) ) :
            foreach ( $categories as $cat ) :
                // 각 카테고리별 최신 글 3개 쿼리
                $query = new WP_Query( array(
                    'cat'                 => $cat->term_id,
                    'posts_per_page'      => 3,
                    'no_found_rows'       => true, 
                    'ignore_sticky_posts' => true,
                ) );

                if ( $query->have_posts() ) : ?>
                    <section class="fw-category-section">
                        <div class="fw-section-header">
                            <div class="fw-section-title-wrap">
                                <span class="fw-section-hash">#</span>
                                <h2 class="fw-section-title"><?php echo esc_html( $cat->name ); ?></h2>
                            </div>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="fw-view-all">
                                View All &rarr;
                            </a>
                        </div>

                        <div class="fw-card-grid">
                            <?php while ( $query->have_posts() ) : $query->the_post(); 
                                get_template_part( 'template-parts/content', 'card' );
                            endwhile; wp_reset_postdata(); ?>
                        </div>
                    </section>
                <?php endif;
            endforeach;
        endif; ?>
    </main>
</div>

<?php get_footer(); ?>
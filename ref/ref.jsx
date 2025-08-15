import React, { useState, useRef, useCallback } from 'react';
import { Play, Pause, Square, Save, FolderOpen, Download, MousePointer, Hand, MessageCircle, Users, Circle, Triangle, RotateCcw, ChevronUp, ChevronDown, Trash2, Plus, Palette, Layers } from 'lucide-react';

const TheaterFlux = () => {
    const [scenes, setScenes] = useState([
        {
            id: 1,
            name: 'Cena 1',
            actors: [],
            objects: [],
            stage: {
                type: 'proscenium',
                color: '#8B4513',
                width: 600,
                height: 400,
                shape: 'rectangle'
            }
        }
    ]);

    const [currentSceneIndex, setCurrentSceneIndex] = useState(0);
    const [selectedElement, setSelectedElement] = useState(null);
    const [selectedTool, setSelectedTool] = useState('select');
    const [isPlaying, setIsPlaying] = useState(false);
    const [isPaused, setIsPaused] = useState(false);
    const [draggedElement, setDraggedElement] = useState(null);
    const [showSpeechDialog, setShowSpeechDialog] = useState(false);
    const [speechText, setSpeechText] = useState('');
    const [zoom, setZoom] = useState(100);
    const [canvasOffset, setCanvasOffset] = useState({ x: 0, y: 0 });
    const [isPanning, setIsPanning] = useState(false);
    const [panStart, setPanStart] = useState({ x: 0, y: 0 });
    const [availableActors, setAvailableActors] = useState([]);
    const [showActorDialog, setShowActorDialog] = useState(false);
    const [newActorName, setNewActorName] = useState('');
    const [selectedActorType, setSelectedActorType] = useState(null);
    const [showStageEditor, setShowStageEditor] = useState(false);
    const [draggedScene, setDraggedScene] = useState(null);

    const canvasRef = useRef(null);
    const currentScene = scenes[currentSceneIndex];

    const tools = [
        { id: 'select', icon: MousePointer, label: 'Selecionar' },
        { id: 'hand', icon: Hand, label: 'Mover Canvas' },
        { id: 'speech', icon: MessageCircle, label: 'Balão de Fala' },
        { id: 'rectangle', icon: Square, label: 'Retângulo' },
        { id: 'circle', icon: Circle, label: 'Círculo' },
        { id: 'triangle', icon: Triangle, label: 'Triângulo' },
    ];

    const stageShapes = [
        { id: 'rectangle', name: 'Retangular', preview: '⬜' },
        { id: 'circle', name: 'Circular', preview: '⭕' },
        { id: 'ellipse', name: 'Elíptico', preview: '⭕' },
        { id: 'hexagon', name: 'Hexágono', preview: '⬡' },
        { id: 'octagon', name: 'Octágono', preview: '⭘' },
        { id: 'thrust', name: 'Thrust', preview: '🏛️' },
        { id: 'horseshoe', name: 'Ferradura', preview: '🧲' },
        { id: 'traverse', name: 'Transversal', preview: '➖' }
    ];

    const actorColors = ['#3b82f6', '#ef4444', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#14b8a6'];

    const addScene = () => {
        const previousScene = scenes[currentSceneIndex];
        const newScene = {
            id: Date.now(),
            name: `Cena ${scenes.length + 1}`,
            actors: previousScene ? [...previousScene.actors.map(actor => ({
                ...actor,
                id: Date.now() + Math.random(),
                speech: actor.speech || ''
            }))] : [],
            objects: previousScene ? [...previousScene.objects.map(obj => ({
                ...obj,
                id: Date.now() + Math.random()
            }))] : [],
            stage: previousScene ? { ...previousScene.stage } : { type: 'proscenium', color: '#8B4513' }
        };

        const newScenes = [...scenes];
        newScenes.splice(currentSceneIndex + 1, 0, newScene);
        setScenes(newScenes);
        setCurrentSceneIndex(currentSceneIndex + 1);
    };

    const removeScene = () => {
        if (scenes.length > 1) {
            const newScenes = scenes.filter((_, i) => i !== currentSceneIndex);
            setScenes(newScenes);
            setCurrentSceneIndex(Math.max(0, currentSceneIndex - 1));
        }
    };

    const duplicateScene = () => {
        const sceneToClone = scenes[currentSceneIndex];
        const newScene = {
            ...sceneToClone,
            id: Date.now(),
            name: `${sceneToClone.name} (Cópia)`,
            actors: sceneToClone.actors.map(actor => ({
                ...actor,
                id: Date.now() + Math.random()
            })),
            objects: sceneToClone.objects.map(obj => ({
                ...obj,
                id: Date.now() + Math.random()
            }))
        };

        const newScenes = [...scenes];
        newScenes.splice(currentSceneIndex + 1, 0, newScene);
        setScenes(newScenes);
        setCurrentSceneIndex(currentSceneIndex + 1);
    };

    const handleCanvasClick = (e) => {
        if (!canvasRef.current) return;

        const rect = canvasRef.current.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        if (selectedTool === 'rectangle') {
            addObject('rectangle', x, y);
        } else if (selectedTool === 'circle') {
            addObject('circle', x, y);
        } else if (selectedTool === 'triangle') {
            addObject('triangle', x, y);
        }
    };

    const addNewActor = () => {
        if (newActorName.trim()) {
            const newActor = {
                id: Date.now(),
                name: newActorName.trim(),
                color: actorColors[availableActors.length % actorColors.length]
            };
            setAvailableActors([...availableActors, newActor]);
            setNewActorName('');
            setShowActorDialog(false);
        }
    };

    const addActorToScene = (actorType) => {
        const newActor = {
            id: Date.now(),
            name: actorType.name,
            x: 300 + Math.random() * 100,
            y: 200 + Math.random() * 100,
            color: actorType.color,
            zIndex: currentScene.actors.length + currentScene.objects.length,
            speech: ''
        };

        const newScenes = [...scenes];
        newScenes[currentSceneIndex].actors.push(newActor);
        setScenes(newScenes);
        setSelectedElement(newActor);
    };

    const updateStage = (newStageProps) => {
        const newScenes = [...scenes];
        newScenes[currentSceneIndex].stage = { ...newScenes[currentSceneIndex].stage, ...newStageProps };
        setScenes(newScenes);
    };

    React.useEffect(() => {
        if (selectedTool === 'hand') {
            document.addEventListener('mousemove', handlePanMove);
            document.addEventListener('mouseup', handlePanEnd);
            return () => {
                document.removeEventListener('mousemove', handlePanMove);
                document.removeEventListener('mouseup', handlePanEnd);
            };
        }
    }, [selectedTool, isPanning, panStart]);

    const addObject = (type, x, y) => {
        const newObject = {
            id: Date.now(),
            type,
            x: x - 25,
            y: y - 25,
            width: 50,
            height: 50,
            color: '#666666',
            zIndex: currentScene.actors.length + currentScene.objects.length
        };

        const newScenes = [...scenes];
        newScenes[currentSceneIndex].objects.push(newObject);
        setScenes(newScenes);
        setSelectedElement(newObject);
    };

    const moveElementLayer = (element, direction) => {
        const newScenes = [...scenes];
        const isActor = element.name !== undefined;
        const elements = isActor ?
            [...newScenes[currentSceneIndex].actors, ...newScenes[currentSceneIndex].objects] :
            [...newScenes[currentSceneIndex].actors, ...newScenes[currentSceneIndex].objects];

        const currentZ = element.zIndex;
        const targetZ = direction === 'up' ? currentZ + 1 : currentZ - 1;

        const targetElement = elements.find(el => el.zIndex === targetZ);
        if (targetElement) {
            element.zIndex = targetZ;
            targetElement.zIndex = currentZ;
        }

        setScenes(newScenes);
    };

    const deleteElement = (element) => {
        const newScenes = [...scenes];
        const isActor = element.name !== undefined;

        if (isActor) {
            newScenes[currentSceneIndex].actors = newScenes[currentSceneIndex].actors.filter(a => a.id !== element.id);
        } else {
            newScenes[currentSceneIndex].objects = newScenes[currentSceneIndex].objects.filter(o => o.id !== element.id);
        }

        setScenes(newScenes);
        setSelectedElement(null);
    };

    const addSpeechBubble = () => {
        if (selectedElement && selectedElement.name && speechText.trim()) {
            const newScenes = [...scenes];
            const actorIndex = newScenes[currentSceneIndex].actors.findIndex(a => a.id === selectedElement.id);
            if (actorIndex !== -1) {
                newScenes[currentSceneIndex].actors[actorIndex].speech = speechText.trim();
                setScenes(newScenes);
            }
            setSpeechText('');
            setShowSpeechDialog(false);
        }
    };

    const handleElementMouseDown = (element, e) => {
        e.stopPropagation();
        setSelectedElement(element);
        if (selectedTool === 'select') {
            setDraggedElement(element);
        }
    };

    const handleMouseMove = useCallback((e) => {
        if (draggedElement && canvasRef.current) {
            const rect = canvasRef.current.getBoundingClientRect();
            const x = Math.max(10, Math.min(760, e.clientX - rect.left));
            const y = Math.max(10, Math.min(430, e.clientY - rect.top));

            const newScenes = [...scenes];
            const isActor = draggedElement.name !== undefined;

            if (isActor) {
                const actorIndex = newScenes[currentSceneIndex].actors.findIndex(a => a.id === draggedElement.id);
                if (actorIndex !== -1) {
                    newScenes[currentSceneIndex].actors[actorIndex].x = x;
                    newScenes[currentSceneIndex].actors[actorIndex].y = y;
                }
            } else {
                const objIndex = newScenes[currentSceneIndex].objects.findIndex(o => o.id === draggedElement.id);
                if (objIndex !== -1) {
                    newScenes[currentSceneIndex].objects[objIndex].x = x - 25;
                    newScenes[currentSceneIndex].objects[objIndex].y = y - 25;
                }
            }

            setScenes(newScenes);
        }
    }, [draggedElement, scenes, currentSceneIndex]);

    const handleMouseUp = useCallback(() => {
        setDraggedElement(null);
    }, []);

    React.useEffect(() => {
        if (draggedElement) {
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
            return () => {
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            };
        }
    }, [draggedElement, handleMouseMove, handleMouseUp]);

    const moveScene = (fromIndex, toIndex) => {
        const newScenes = [...scenes];
        const [movedScene] = newScenes.splice(fromIndex, 1);
        newScenes.splice(toIndex, 0, movedScene);
        setScenes(newScenes);
        setCurrentSceneIndex(toIndex);
    };

    const playScenes = () => {
        setIsPlaying(true);
        setIsPaused(false);
        setCurrentSceneIndex(0); // Sempre começar da primeira cena

        let sceneIndex = 0;

        const playNext = () => {
            if (sceneIndex < scenes.length && isPlaying && !isPaused) {
                setCurrentSceneIndex(sceneIndex);
                sceneIndex++;
                if (sceneIndex < scenes.length) {
                    setTimeout(playNext, 2500);
                } else {
                    setIsPlaying(false);
                }
            }
        };

        setTimeout(playNext, 100); // Pequeno delay para mostrar a primeira cena
    };

    const handleZoom = (delta) => {
        const newZoom = Math.max(50, Math.min(200, zoom + delta));
        setZoom(newZoom);
    };

    const handlePanStart = (e) => {
        if (selectedTool === 'hand') {
            setIsPanning(true);
            setPanStart({ x: e.clientX - canvasOffset.x, y: e.clientY - canvasOffset.y });
        }
    };

    const handlePanMove = (e) => {
        if (isPanning && selectedTool === 'hand') {
            setCanvasOffset({
                x: e.clientX - panStart.x,
                y: e.clientY - panStart.y
            });
        }
    };

    const handlePanEnd = () => {
        setIsPanning(false);
    };

    const getStageStyle = (stage) => {
        const baseStyle = {
            backgroundColor: stage.color,
            opacity: 0.3
        };

        switch (stage.shape) {
            case 'circle':
                return { ...baseStyle, borderRadius: '50%' };
            case 'ellipse':
                return { ...baseStyle, borderRadius: '50%', transform: 'scaleX(1.5)' };
            case 'hexagon':
                return { ...baseStyle, clipPath: 'polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%)' };
            case 'octagon':
                return { ...baseStyle, clipPath: 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)' };
            case 'thrust':
                return { ...baseStyle, clipPath: 'polygon(0% 20%, 100% 20%, 80% 100%, 20% 100%)' };
            case 'horseshoe':
                return { ...baseStyle, borderRadius: '50% 50% 0 0', clipPath: 'polygon(0% 0%, 100% 0%, 100% 70%, 80% 100%, 20% 100%, 0% 70%)' };
            case 'traverse':
                return { ...baseStyle, height: '60%', top: '20%' };
            default:
                return baseStyle;
        }
    };

    const pausePlay = () => {
        setIsPaused(!isPaused);
    };

    const stopPlay = () => {
        setIsPlaying(false);
        setIsPaused(false);
    };

    const allElements = [...currentScene.actors, ...currentScene.objects].sort((a, b) => a.zIndex - b.zIndex);

    return (
        <div className="h-screen bg-gray-100 flex flex-col">
            {/* Header */}
            <div className="bg-white border-b border-gray-300 px-4 py-2 flex items-center justify-between">
                <div className="flex items-center gap-4">
                    <h1 className="text-lg font-semibold text-gray-800">Theatre Flux</h1>
                    <div className="flex gap-2">
                        <button className="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            <Save size={14} />
                            Salvar
                        </button>
                        <button className="flex items-center gap-1 px-3 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700">
                            <FolderOpen size={14} />
                            Carregar
                        </button>
                        <button className="flex items-center gap-1 px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            <Download size={14} />
                            Exportar
                        </button>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <button
                        onClick={playScenes}
                        disabled={isPlaying}
                        className="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        <Play size={14} />
                        Reproduzir
                    </button>
                    <button
                        onClick={pausePlay}
                        disabled={!isPlaying}
                        className="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50"
                    >
                        <Pause size={14} />
                        Pausar
                    </button>
                    <button
                        onClick={stopPlay}
                        disabled={!isPlaying && !isPaused}
                        className="flex items-center gap-1 px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 disabled:opacity-50"
                    >
                        <Square size={14} />
                        Parar
                    </button>
                </div>
            </div>

            <div className="flex flex-1">
                {/* Toolbar */}
                <div className="w-14 bg-gray-200 border-r border-gray-300 flex flex-col">
                    {tools.map(tool => (
                        <button
                            key={tool.id}
                            onClick={() => setSelectedTool(tool.id)}
                            className={`w-full h-12 flex items-center justify-center border-b border-gray-300 hover:bg-gray-300 ${selectedTool === tool.id ? 'bg-blue-200' : ''
                                }`}
                            title={tool.label}
                        >
                            <tool.icon size={20} className="text-gray-700" />
                        </button>
                    ))}

                    <div className="flex-1"></div>

                    {/* Zoom controls */}
                    <div className="border-t border-gray-300 p-2">
                        <button
                            onClick={() => handleZoom(10)}
                            className="w-full mb-1 p-1 hover:bg-gray-300 rounded text-xs"
                            title="Zoom In"
                        >
                            +
                        </button>
                        <div className="text-xs text-center mb-1">{zoom}%</div>
                        <button
                            onClick={() => handleZoom(-10)}
                            className="w-full p-1 hover:bg-gray-300 rounded text-xs"
                            title="Zoom Out"
                        >
                            -
                        </button>
                    </div>
                </div>

                {/* Main Canvas Area */}
                <div className="flex-1 flex flex-col">
                    {/* Toolbar secundária */}
                    <div className="bg-gray-50 border-b border-gray-300 px-4 py-2 flex items-center gap-4">
                        <div className="flex items-center gap-4">
                            <button
                                onClick={addScene}
                                className="p-1 hover:bg-gray-200 rounded"
                                title="Nova Cena (cópia da atual)"
                            >
                                <Plus size={16} />
                            </button>
                            <button
                                onClick={duplicateScene}
                                className="p-1 hover:bg-gray-200 rounded"
                                title="Duplicar Cena"
                            >
                                <RotateCcw size={16} />
                            </button>
                            <button
                                onClick={removeScene}
                                disabled={scenes.length === 1}
                                className="p-1 hover:bg-gray-200 rounded disabled:opacity-50"
                                title="Remover Cena"
                            >
                                <Trash2 size={16} />
                            </button>
                            <button
                                onClick={() => setShowActorDialog(true)}
                                className="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
                            >
                                Cadastrar Ator
                            </button>
                            <button
                                onClick={() => setShowStageEditor(true)}
                                className="px-3 py-1 bg-purple-600 text-white text-sm rounded hover:bg-purple-700"
                            >
                                Editor de Palco
                            </button>
                        </div>

                        <div className="flex-1"></div>

                        <span className="text-sm text-gray-600">
                            {allElements.length} elemento(s) no canvas
                        </span>
                    </div>

                    {/* Canvas */}
                    <div className="flex-1 p-4 overflow-hidden bg-gray-100">
                        <div
                            className="w-full h-full flex items-center justify-center relative"
                            onWheel={(e) => {
                                e.preventDefault();
                                handleZoom(e.deltaY > 0 ? -10 : 10);
                            }}
                        >
                            <div
                                ref={canvasRef}
                                onClick={handleCanvasClick}
                                onMouseDown={handlePanStart}
                                className="bg-white border-2 border-gray-300 relative"
                                style={{
                                    width: `${currentScene.stage.width || 600}px`,
                                    height: `${currentScene.stage.height || 400}px`,
                                    transform: `scale(${zoom / 100}) translate(${canvasOffset.x}px, ${canvasOffset.y}px)`,
                                    cursor: selectedTool === 'hand' ? (isPanning ? 'grabbing' : 'grab') : 'crosshair',
                                    transformOrigin: 'center center'
                                }}
                            >
                                {/* Stage Background */}
                                <div
                                    className="absolute inset-0"
                                    style={getStageStyle(currentScene.stage)}
                                ></div>

                                {/* Elements */}
                                {allElements.map(element => {
                                    const isActor = element.name !== undefined;
                                    return (
                                        <div key={element.id} style={{ zIndex: element.zIndex }}>
                                            {/* Speech Bubble */}
                                            {isActor && element.speech && (
                                                <div
                                                    className="absolute bg-white border-2 border-gray-300 rounded-lg px-2 py-1 text-xs shadow-lg"
                                                    style={{
                                                        left: element.x + 20,
                                                        top: element.y - 40,
                                                        maxWidth: '120px',
                                                        wordWrap: 'break-word'
                                                    }}
                                                >
                                                    {element.speech}
                                                    <div
                                                        className="absolute w-0 h-0"
                                                        style={{
                                                            left: '-8px',
                                                            top: '20px',
                                                            borderTop: '8px solid transparent',
                                                            borderBottom: '8px solid transparent',
                                                            borderRight: '8px solid white'
                                                        }}
                                                    ></div>
                                                </div>
                                            )}

                                            {/* Element */}
                                            <div
                                                className={`absolute cursor-pointer ${selectedElement?.id === element.id ? 'ring-2 ring-blue-400' : ''
                                                    } ${isActor ? 'hover:scale-110' : 'hover:scale-105'}`}
                                                style={{
                                                    left: element.x - (isActor ? 20 : 0),
                                                    top: element.y - (isActor ? 20 : 0),
                                                    width: isActor ? '40px' : `${element.width}px`,
                                                    height: isActor ? '40px' : `${element.height}px`,
                                                    backgroundColor: isActor ? element.color : element.color,
                                                    borderRadius: isActor ? '50%' : element.type === 'circle' ? '50%' : '0',
                                                    border: isActor ? '3px solid white' : element.type === 'circle' ? '2px solid white' : '2px solid white',
                                                    transition: 'transform 0.2s, box-shadow 0.2s',
                                                    opacity: isActor ? 1 : 0.8
                                                }}
                                                onMouseDown={(e) => handleElementMouseDown(element, e)}
                                            >
                                                {isActor ? (
                                                    <div className="w-full h-full flex items-center justify-center text-white text-sm font-bold">
                                                        {element.name.charAt(0).toUpperCase()}
                                                    </div>
                                                ) : element.type === 'triangle' ? (
                                                    <div
                                                        className="w-0 h-0"
                                                        style={{
                                                            borderLeft: '20px solid transparent',
                                                            borderRight: '20px solid transparent',
                                                            borderBottom: '35px solid white',
                                                            margin: '5px auto'
                                                        }}
                                                    ></div>
                                                ) : null}
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Sidebar */}
                <div className="w-80 bg-white border-l border-gray-300 flex flex-col">
                    {/* Scene Navigator */}
                    <div className="p-4 border-b border-gray-300">
                        <h3 className="text-sm font-semibold mb-2">Cenas</h3>
                        <div className="flex gap-2 overflow-x-auto pb-2">
                            {scenes.map((scene, index) => (
                                <div
                                    key={scene.id}
                                    draggable
                                    onDragStart={() => setDraggedScene(index)}
                                    onDragOver={(e) => e.preventDefault()}
                                    onDrop={() => {
                                        if (draggedScene !== null && draggedScene !== index) {
                                            moveScene(draggedScene, index);
                                            setDraggedScene(null);
                                        }
                                    }}
                                    className={`flex-shrink-0 w-16 h-12 text-xs border rounded cursor-pointer relative ${index === currentSceneIndex
                                            ? 'border-blue-500 bg-blue-50 text-blue-700'
                                            : 'border-gray-300 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setCurrentSceneIndex(index)}
                                >
                                    <div className="w-full h-full flex items-center justify-center">
                                        {index + 1}
                                    </div>
                                    {isPlaying && index === currentSceneIndex && (
                                        <div className="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    )}
                                </div>
                            ))}
                        </div>
                        <input
                            type="text"
                            value={currentScene.name}
                            onChange={(e) => {
                                const newScenes = [...scenes];
                                newScenes[currentSceneIndex].name = e.target.value;
                                setScenes(newScenes);
                            }}
                            className="w-full mt-2 px-2 py-1 text-sm border border-gray-300 rounded"
                        />
                    </div>

                    {/* Atores Cadastrados */}
                    <div className="p-4 border-b border-gray-300">
                        <h3 className="text-sm font-semibold mb-2">Atores Disponíveis</h3>
                        {availableActors.length === 0 ? (
                            <p className="text-xs text-gray-500">Nenhum ator cadastrado</p>
                        ) : (
                            <div className="space-y-1">
                                {availableActors.map((actor) => (
                                    <button
                                        key={actor.id}
                                        onClick={() => addActorToScene(actor)}
                                        className="w-full flex items-center gap-2 p-2 text-xs rounded hover:bg-gray-100 border border-gray-200"
                                    >
                                        <div
                                            className="w-6 h-6 rounded-full border-2 border-white"
                                            style={{ backgroundColor: actor.color }}
                                        ></div>
                                        <span>{actor.name}</span>
                                    </button>
                                ))}
                            </div>
                        )}
                    </div>
                    {selectedElement && (
                        <div className="p-4 border-b border-gray-300">
                            <h3 className="text-sm font-semibold mb-2">Propriedades</h3>
                            <div className="space-y-2">
                                <div>
                                    <label className="text-xs text-gray-600">Nome:</label>
                                    <input
                                        type="text"
                                        value={selectedElement.name || 'Objeto'}
                                        onChange={(e) => {
                                            const newScenes = [...scenes];
                                            const isActor = selectedElement.name !== undefined;
                                            if (isActor) {
                                                const actorIndex = newScenes[currentSceneIndex].actors.findIndex(a => a.id === selectedElement.id);
                                                if (actorIndex !== -1) {
                                                    newScenes[currentSceneIndex].actors[actorIndex].name = e.target.value;
                                                }
                                            }
                                            setScenes(newScenes);
                                            setSelectedElement({ ...selectedElement, name: e.target.value });
                                        }}
                                        className="w-full px-2 py-1 text-xs border border-gray-300 rounded"
                                    />
                                </div>

                                {selectedElement.name && (
                                    <div>
                                        <button
                                            onClick={() => setShowSpeechDialog(true)}
                                            className="w-full px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700"
                                        >
                                            Adicionar Fala
                                        </button>
                                    </div>
                                )}

                                <div className="flex gap-2">
                                    <button
                                        onClick={() => moveElementLayer(selectedElement, 'up')}
                                        className="flex-1 px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700"
                                    >
                                        <ChevronUp size={12} className="mx-auto" />
                                    </button>
                                    <button
                                        onClick={() => moveElementLayer(selectedElement, 'down')}
                                        className="flex-1 px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700"
                                    >
                                        <ChevronDown size={12} className="mx-auto" />
                                    </button>
                                    <button
                                        onClick={() => deleteElement(selectedElement)}
                                        className="flex-1 px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700"
                                    >
                                        <Trash2 size={12} className="mx-auto" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Elements List */}
                    <div className="flex-1 p-4">
                        <h3 className="text-sm font-semibold mb-2">Elementos da Cena</h3>
                        <div className="space-y-1">
                            {allElements.map(element => {
                                const isActor = element.name !== undefined;
                                return (
                                    <div
                                        key={element.id}
                                        onClick={() => setSelectedElement(element)}
                                        className={`flex items-center gap-2 p-2 text-xs rounded cursor-pointer ${selectedElement?.id === element.id ? 'bg-blue-100' : 'hover:bg-gray-100'
                                            }`}
                                    >
                                        <div
                                            className="w-3 h-3 rounded border"
                                            style={{ backgroundColor: element.color }}
                                        ></div>
                                        <span className="flex-1">
                                            {isActor ? element.name : `${element.type} ${element.id}`}
                                        </span>
                                        <span className="text-gray-500">z:{element.zIndex}</span>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>

            {/* Actor Registration Dialog */}
            {showActorDialog && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white p-6 rounded-lg w-96">
                        <h3 className="text-lg font-semibold mb-4">Cadastrar Novo Ator</h3>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-2">Nome do Ator:</label>
                                <input
                                    type="text"
                                    value={newActorName}
                                    onChange={(e) => setNewActorName(e.target.value)}
                                    placeholder="Digite o nome do ator"
                                    className="w-full px-3 py-2 border border-gray-300 rounded"
                                    onKeyPress={(e) => e.key === 'Enter' && addNewActor()}
                                />
                            </div>
                        </div>
                        <div className="flex gap-2 mt-6">
                            <button
                                onClick={addNewActor}
                                disabled={!newActorName.trim()}
                                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                            >
                                Cadastrar
                            </button>
                            <button
                                onClick={() => {
                                    setShowActorDialog(false);
                                    setNewActorName('');
                                }}
                                className="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Stage Editor Dialog */}
            {showStageEditor && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white p-6 rounded-lg w-96 max-h-96 overflow-y-auto">
                        <h3 className="text-lg font-semibold mb-4">Editor de Palco</h3>
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium mb-2">Formato do Palco:</label>
                                <div className="grid grid-cols-2 gap-2">
                                    {stageShapes.map((shape) => (
                                        <button
                                            key={shape.id}
                                            onClick={() => updateStage({ shape: shape.id })}
                                            className={`p-2 text-xs border rounded flex items-center gap-2 ${currentScene.stage.shape === shape.id
                                                    ? 'border-blue-500 bg-blue-50'
                                                    : 'border-gray-300 hover:bg-gray-50'
                                                }`}
                                        >
                                            <span className="text-lg">{shape.preview}</span>
                                            <span>{shape.name}</span>
                                        </button>
                                    ))}
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-2">Cor do Palco:</label>
                                <input
                                    type="color"
                                    value={currentScene.stage.color}
                                    onChange={(e) => updateStage({ color: e.target.value })}
                                    className="w-full h-10 border border-gray-300 rounded"
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-2">Largura:</label>
                                    <input
                                        type="number"
                                        value={currentScene.stage.width || 600}
                                        onChange={(e) => updateStage({ width: parseInt(e.target.value) })}
                                        min="400"
                                        max="1000"
                                        className="w-full px-3 py-2 border border-gray-300 rounded"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-2">Altura:</label>
                                    <input
                                        type="number"
                                        value={currentScene.stage.height || 400}
                                        onChange={(e) => updateStage({ height: parseInt(e.target.value) })}
                                        min="300"
                                        max="800"
                                        className="w-full px-3 py-2 border border-gray-300 rounded"
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="flex gap-2 mt-6">
                            <button
                                onClick={() => setShowStageEditor(false)}
                                className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            >
                                Aplicar
                            </button>
                            <button
                                onClick={() => setShowStageEditor(false)}
                                className="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
                            >
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            )}
            {showSpeechDialog && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white p-6 rounded-lg w-96">
                        <h3 className="text-lg font-semibold mb-4">Adicionar Fala</h3>
                        <textarea
                            value={speechText}
                            onChange={(e) => setSpeechText(e.target.value)}
                            placeholder="Digite a fala do personagem..."
                            className="w-full h-24 px-3 py-2 border border-gray-300 rounded resize-none"
                        />
                        <div className="flex gap-2 mt-4">
                            <button
                                onClick={addSpeechBubble}
                                className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            >
                                Adicionar
                            </button>
                            <button
                                onClick={() => {
                                    setShowSpeechDialog(false);
                                    setSpeechText('');
                                }}
                                className="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default TheaterFlux;